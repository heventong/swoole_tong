<?php
require_once ('vendor/autoload.php');


$http = new Swoole\Http\Server("0.0.0.0", 9501);
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/test', function (){
        return "xxxx";
    });
    $r->addRoute('GET', '/test1', function (){
        return "xxxx";
    });

});

$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$http->on("request", function ($request, $response)use ($dispatcher) {
    $myrequest = App\core\Request::init($request);
    $response->header("Content-Type", "text/plain");
//    $response->end("Hello World\n");
//    if($request->server['request_uri']=="/test"){
//        $response->end("<h1>test</h1>");
//    }
    $ret=$dispatcher->dispatch($myrequest->getMethod(),$myrequest->getUri());
    switch ($ret[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $response->status(404);
            $response->end();
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $response->status(405);
            $response->end();

            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $ret[1];
            $response->end( $handler());
            break;
    }


});

$http->start();
