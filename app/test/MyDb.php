<?php
namespace App\test;

class MyDb{
    private $db;
    public function __construct($connInfo = '')
    {

    }
    public function queryForRows($sql){
        return ['user_id'=>101,'user_name'=>'tong'];
    }
}