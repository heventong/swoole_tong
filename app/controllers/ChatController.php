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
 * @Bean(name="chat")
 */
class ChatController
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
     * @RequestMapping(value="/chat/test")
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
     * @RequestMapping(value="/chat/{uid:\d+}")
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



}