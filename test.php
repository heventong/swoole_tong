<?php
class A{

    public $name = '1';
    public function test(){
            echo 'test';
    }
}


class MyA{
    private static $a = '';

    private function __construct(){}

    private function __clone(){}

    public static function getInstance(){
        if(!self::$a instanceof A){
            self::$a = new A;
        }
        return self::$a;
    }
}

$a = MyA::getInstance();
$a -> test();








