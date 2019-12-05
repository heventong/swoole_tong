<?php
namespace App\controllers;
use Core\annotations\Bean;
use Core\annotations\Value;

/**
 * @Bean(name="abc")
 */
class UserController{
    /**
     * @Value(name="version")
     */
    public $version="1.0";

}