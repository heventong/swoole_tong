<?php
namespace App\controllers;
use Core\annotations\Bean;

/**
 * @Bean()
 */
class TestController{
    public $version="2.0";
    public $name;
}