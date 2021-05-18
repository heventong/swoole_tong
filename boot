<?php
require_once "vendor/autoload.php";
require_once __DIR__."/app/config/define.php"; //自定义配置
\Swoole\Runtime::enableCoroutine(true);
use Swoole\Process;
use Core\server\HttpServer;
use Core\server\WebsocketServer;
if($argc == 2){
    $cmd = $argv[1];
    if($cmd=='start'){
        $http = new HttpServer();
        $http->run();
        //$ws = new WebsocketServer();
        //$ws->run();
    }else if($cmd=='stop'){
        $getpid=intval(file_get_contents(ROOT_PATH."/Tong.pid")); //获取上一次程序运行的 master_id
        if($getpid && trim($getpid)!=0){
            Process::kill($getpid);
        }
//        $getwspid=intval(file_get_contents(ROOT_PATH."/Tongws.pid")); //获取上一次程序运行的 master_id
//        if($getwspid && trim($getwspid)!=0){
//            Process::kill($getwspid);
//        }
    }else if($cmd=='restart'){
        $getpid=intval(file_get_contents(ROOT_PATH."/Tong.pid")); //获取上一次程序运行的 master_id
        if($getpid && trim($getpid)!=0){
            Process::kill($getpid);
        }
        $http = new HttpServer();
        $http->run();
    }else{
        echo "无效命令".PHP_EOL;
    }
}
