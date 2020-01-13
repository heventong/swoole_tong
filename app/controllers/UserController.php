<?php
namespace App\controllers;

use Core\annotations\Bean;
use Core\annotations\RequestMapping;
use Core\annotations\Value;
use Core\annotations\DB;
use Core\http\Request;
use Core\http\Response;
use Core\init\MyDB;
use DI\Annotation\Inject;

/**
 * @Bean(name="user")
 */
class UserController{

    /**
     * @DB
     * @var MyDB
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


}