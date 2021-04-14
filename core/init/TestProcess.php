<?php
namespace Core\init;
use App\helper\Mail;
use Core\BeanFactory;
use Core\helper\FileHelper;
use Swoole\Process;

class TestProcess{
    private  $md5file;
    public function run(){
        return new Process(function(\Swoole\Process $childProcess){
            while(true){
//                file_put_contents("./Tong.log","测试通".PHP_EOL,FILE_APPEND);
                /*sleep(3);
                $is_mail = file_get_contents(ROOT_PATH.'/runtime/sign/mail.log');
                $uptime = $childProcess->exec("/bin/sh",['-c','sudo uptime']);
                $childProcess->write($uptime);
                $string = end(explode("  ",$uptime));
                $load_status = explode(": ",explode(",",$string)[0])[1];
                var_dump($load_status);
                if(empty($is_mail) && (int)$load_status >=1 ) {
                    Mail::send(Mail::getInstance(), "wentong.he@tdnnic.org", "负载超1啦");

                    $client = new \Predis\Client([
                        'scheme' => 'tcp',
                        'host' => '127.0.0.1',
                        'port' => 6379,
                        'password' => 'tong123'
                    ]);
                    $res = $client->set('a', 'bar');
                    file_put_contents(ROOT_PATH.'/runtime/sign/mail.log',$res);
                }*/
//                $res = $this->db->table("users")->first();
//                $top = system("sudo ps aux|head -1;ps aux|grep -v PID|sort -rn -k +3|head");
                $md5_value=FileHelper::getFileMd5(ROOT_PATH."/app/*","/app/config");
                if($this->md5file==""){
                    $this->md5file=$md5_value;
                    continue;
                }
                if(strcmp($this->md5file,$md5_value)!==0){ //代表文件有改动
                    echo "reloading....".PHP_EOL;
                    $getpid=intval(file_get_contents(ROOT_PATH."/Tong.pid"));
                    Process::kill($getpid,SIGUSR1);
                    $this->md5file=$md5_value;
                    echo "reloaded".PHP_EOL;
                }

            }
        });
    }
}