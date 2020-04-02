<?php
use Swoole\Http\Request;
use Swoole\Http\Response;

require_once ('vendor/autoload.php');
require_once __DIR__."/app/config/define.php"; //自定义配置
\Core\BeanFactory::init();//初始化Bean工厂



$dispatcher = \Core\BeanFactory::getBean("RouterCollector")->getDispatcher(); //从IoC容器中加载出 我们所需要的Bean

$http = new Swoole\Http\Server("0.0.0.0", 9501);

$http->set(array(
    'worker_num' => 1,
    'daemonize' => false,
    'enable_static_handler'=>true,
    'document_root'=>"/Data/apps/www/web-music/app"
));
$http->on('request', function (Request $request,Response $response) use($dispatcher) {
    $myrequest=\Core\http\Request::init($request);
    $myresponse=\Core\http\Response::init($response);
    $routeInfo = $dispatcher->dispatch($myrequest->getMethod(),$myrequest->getUri() );
    //[1,$handler,$var]
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $response->status(404);
            $response->end();
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $response->status(405);
            $response->end();
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars=$routeInfo[2];
            $ext_vars=[$myrequest,$myresponse];
            $myresponse->setBody($handler($vars,$ext_vars));//设置响应body部分
            $myresponse->end();//最终执行的目标方法,加入了参数
            break;
    }

});

$http->start();
