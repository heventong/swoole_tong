<?php

namespace App\controllers;

use App\helper\Mail;
use App\models\Users;
use Core\annotations\Bean;
use Core\annotations\RequestMapping;
use Core\annotations\Value;
use Core\annotations\DB;
use Core\http\Request;
use Core\http\Response;
use Core\init\MyDB;
use Core\init\MyMQ;
use DI\Annotation\Inject;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use \Swoole\Coroutine as Co;

/**
 * @Bean(name="user")
 */
class UserController
{

    /**
     * @DB(source="default")
     * @var MyDB
     */
    private $db;
    /**
     * @DB(source="abc")
     * @var MyDB
     */
    private $db2;
    /**
     * @Value(name="version")
     */
    public $version;

    /**
     * @RequestMapping(value="/test")
     */
    public function test()
    {
//        $this->db->select("select sleep(10)");
//        $res = Mail::send(Mail::getInstance(),"13802441847@139.com","内容");

//        $client = new \Predis\Client([
//            'scheme' => 'tcp',
//            'host'   => '127.0.0.1',
//            'port'   => 6379,
//            'password' =>'tong123'
//        ]);
//        $client->set('foo1', 'bar');
//        $value = $client->get('foo12');
//        return $value;




        $ret = $this->db2->table("users")->first();
        $res = $this->db->table("users")->first();
        return $res;
    }

    /**
     * @RequestMapping(value="/user/{uid:\d+}")
     */
    public function user(int $uid, Request $request, Response $response)
    {
        $user_id = $uid;
        $user = ['user_id' => $user_id, 'user_name' => 'tong', 'user_score' => rand(1, 100)];
        $users = $this->db->table('users')->find($user_id);
        if($users){
           return $users;
        }

        $res = $this->db->table('users')->insert($user);
        return $res;
    }

    /**
     * @RequestMapping(value="/curd")
     */
    public function curd()
    {
//        $start = microtime(true);
//        echo "start~~~~~~~~".$start.PHP_EOL;
        for($i=1;$i<=1000;$i++) {
            $user= [];
            for($j=1;$j<=3000;$j++) {
                $user_id = $this->uuid();
                $user[] = ['user_id' => $user_id, 'user_name' => $this->getChar(rand(1,8)), 'user_score' => rand(1, 100)];
            }

//        echo "uuid~~~~~~~~".(microtime(true)-$start).PHP_EOL;
//echo $user_id;

//        echo "rand~~~~~~~~".(microtime(true)-$start).PHP_EOL;
//        Users::
            $in_res = $this->db->table('users')->insert($user);
        }
//        echo "insert~~~~~~~~".(microtime(true)-$start).PHP_EOL;
//        $user = ['user_name' => 'to1ng', 'user_score' => rand(1, 100)];
//        $this->db->table('users')->where(['user_id' => $user_id])->update($user);
        $res = $this->db->table("users")->orderBy('id desc')->get();
//        echo "get~~~~~~~~".(microtime(true)-$start).PHP_EOL;
//        $this->db->table('users')->where(['user_id' => $user_id])->delete();
        return (bool)$in_res;
    }

    /**
     * 随机生成汉子
     * @param $num
     * @return string
     */
    function getChar($num)  // $num为生成汉字的数量

    {

        $b = '';

        for ($i=0; $i<$num; $i++) {

            // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节

            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));

            // 转码

            $b .= iconv('GB2312', 'UTF-8', $a);

        }

        return $b;

    }
    /**
    *生成唯一标志
    *标准的UUID格式为：xxxxxxxx-xxxx-xxxx-xxxxxx-xxxxxxxxxx(8-4-4-4-12)
    **/

    public function uuid()
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr ( $chars, 0, 8 ) . '-'
                . substr ( $chars, 8, 4 ) . '-'
                . substr ( $chars, 12, 4 ) . '-'
                . substr ( $chars, 16, 4 ) . '-'
                . substr ( $chars, 20, 12 );
        return $uuid;
    }
 


}
