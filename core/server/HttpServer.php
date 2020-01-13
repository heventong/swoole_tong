<?php
namespace Core\server;

use Core\init\TestProcess;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class HttpServer{
    private $server;
    private $dispatcher;
    public function __construct()
    {
        $this->server = new \Swoole\Http\Server("0.0.0.0", 9501);
        $this->server ->set(array(
            'worker_num' => 8,
            'daemonize' => false,
        ));
        $this->server ->on('request', [$this,"onRequest"]);
        $this->server ->on('Start', [$this,"onStart"]);
        $this->server ->on('ShutDown', [$this,"onShutDown"]);
        $this->server ->on('WorkerStart', [$this,"OnWorkerStart"]);
        $this->server->on("ManagerStart",[$this,"onManagerStart"]);
    }
    public function OnWorkerStart(Server $server,$wid){
        cli_set_process_title('tong worker');
        \Core\BeanFactory::init();//初始化Bean工厂
        $this->dispatcher = \Core\BeanFactory::getBean('RouterCollector')->getDispatcher();
    }

    public function onRequest(Request $request,Response $response){
        $myrequest=\Core\http\Request::init($request);
        $myresponse=\Core\http\Response::init($response);
        $routeInfo = $this->dispatcher->dispatch($myrequest->getMethod(),$myrequest->getUri() );
        //[1,$handler,$var]
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $response->status(404);
                $response->end();
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $response->status(405);
                $response->end();
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars=$routeInfo[2];
                $ext_vars=[$myrequest,$myresponse];
                $myresponse->setBody($handler($vars,$ext_vars));//设置响应body部分
                $myresponse->end();//最终执行的目标方法,加入了参数
                break;
        }

    }
    public function onManagerStart(Server $server){
        cli_set_process_title("tong manager");
    }
    public function onStart(Server $server){
        cli_set_process_title("tong master");
        $mid= $server->master_pid;
        file_put_contents("./Tong.pid",$mid);
    }
    public function onShutDown(Server $server){
        unlink("./Tong.pid");
    }

    public function run(){
        $p=new TestProcess();
        $this->server->addProcess($p->run());
        $this->server->start();
    }


}