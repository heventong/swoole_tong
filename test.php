<?php


require_once ('vendor/autoload.php');
require_once (__DIR__.'/app/config/define.php');

\Core\BeanFactory::init();

$user = \Core\BeanFactory::getBean('abc');
$user = \Core\BeanFactory::testBean();
var_dump($user);