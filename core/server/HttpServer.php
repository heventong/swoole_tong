<?php
namespace Core\server;

use Core\BeanFactory;
use Core\init\TestProcess;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Server;

class HttpServer{
    private $server;
    private $dispatcher;
    public function __construct()
    {
        $this->server = new Server("0.0.0.0", 9501);
        $this->server ->set(array(
            'worker_num' => 1,
            'daemonize'=>true,
            "open_websocket_protocol" => true,
            //心跳检测
            'heartbeat_check_interval' => 60,
            'heartbeat_idle_time' => 600,
//            'daemonize' => false,
            'document_root'=>ROOT_PATH.'/web/app',
            'enable_static_handler'=>true,
            "enable_coroutine"=> true,
            'task_enable_coroutine' => true,
            'redirect_stdin_stdout' => true,
            "log_file" => ROOT_PATH.'/runtime/logs/swoole.log'
        ));
        $this->server ->on('request', [$this,"onRequest"]);
        $this->server ->on('Start', [$this,"onStart"]);
        $this->server ->on('ShutDown', [$this,"onShutDown"]);
        $this->server ->on('WorkerStart', [$this,"OnWorkerStart"]);
        $this->server->on("ManagerStart",[$this,"onManagerStart"]);
        $this->server->on("open", [$this,"OnOpen"]);

        $this->server->on("message", [$this,"OnMessage"]);

        // $this->server->on('receive', [$this,"OnReceive"]);websocket失效

    }

    public function OnReceive(Server $server, $fd, $reactor_id, $data) {
        $websocket = $server->ports[0];
        foreach ($websocket->connections as $_fd) {
            if ($server->exist($_fd)) {
                $server->push($_fd, "this is server onReceive");
            }
        }
        $server->send($fd, 'receive: '.$data);
    }
    public function OnMessage(Server $server, $frame) {
//        echo "receive1 from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
//        $server->push($frame->fd, "this is server OnMessage11");
        $data = $frame->data;
        foreach ($server->connections as $fd) {
            $server->push($fd, $data);//循环广播
        }
    }
    public function OnOpen(Server $server, Request $request) {

//        echo "new WebSocket Client, fd={$request->fd}\n";
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
//var_dump($routeInfo[0],$myrequest->getMethod(),$myrequest->getUri());
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
        file_put_contents(ROOT_PATH."/Tong.pid",$mid);
    }
    public function onShutDown(Server $server){
        unlink(ROOT_PATH."/Tong.pid");
    }

    public function run(){
        $p=new TestProcess();
        $this->server->addProcess($p->run());
        $this->server->start();
    }


}
