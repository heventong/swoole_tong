<?php
namespace App\controllers;

use Core\annotations\Bean;
use Core\annotations\RequestMapping;
use Core\annotations\Value;

/**
 * @Bean(name="user")
 */
class UserController{

    /**
     * @Value(name="version")
     */
    public $version;

    /**
     * @RequestMapping(value="/test")
     */
    public function test(){
        return "test";
    }
    /**
     * @RequestMapping(value="/user/{uid:\d+}")
     */
    public function user(int $uid){
        return "tong".$uid;
    }


}