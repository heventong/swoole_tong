<?php
namespace App\test;

use DI\Annotation\Inject;

class MyUser{
    private $mydb;

    /**
     * @Inject()
     * @param MyDb $DB
     */
    public function __construct(MyDb $DB)
    {
        $this->mydb = $DB;
    }
    public function getAllUsers():array {
        return $this->mydb->queryForRows("select * from users");
    }


    /**
     * @Inject
     * @var MyRedis
     */
    public $myredis;
}