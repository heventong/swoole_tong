<?php
namespace App\controllers;

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

/**
 * @Bean(name="user")
 */
class UserController{

    /**
     * @DB
     * @var MyDB
     * @var MyMQ
     */
    private $db;
    /**
     * @Value(name="version")
     */
    public $version;

    /**
     * @RequestMapping(value="/test")
     */
    public function test(){
        return $this->db->table("users")->get();
    }
    /**
     * @RequestMapping(value="/user/{uid:\d+}")
     */
    public function user(int $uid ,Request $request,Response $response){
//        return 1;
        return "通".$uid;
//        return ['name'=>'通'];
    }

    /**
     * @RequestMapping(value="/curd")
     */
    public function curd(){
        $user_id = rand(1,100);
        $user =['user_id'=>$user_id,'user_name'=>'tong','user_score'=>rand(1,100)];
//        Users::
        $this->db->table('users')->insert($user);
        $user =['user_name'=>'to1ng','user_score'=>rand(1,100)];
        $this->db->table('users')->where(['user_id'=>$user_id])->update($user);
        $res = $this->db->table("users")->get();
        $this->db->table('users')->where(['user_id'=>$user_id])->delete();
        return $res;
    }


}