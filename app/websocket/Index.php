<?php
namespace App\websocket;
use Core\init\MyDB;
use Core\annotations\DB;
use Core\annotations\Bean;
use Core\annotations\RequestMapping;

/**
 * @Bean(name="ws/index")
 */
class Index {
    /**
     * @DB(source="default")
     * @var MyDB
     */
    private $db;

    /**
     * @RequestMapping(value="/index")
     */
    public function index(){
        $res= $this->db->table("users")->first();
        return $res;
    }
}