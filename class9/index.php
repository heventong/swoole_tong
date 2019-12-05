<?php
require_once __DIR__."/../vendor/autoload.php";

$builder = new \DI\ContainerBuilder();
$builder->useAnnotations(true);
$container = $builder->build();
$myuser = $container->get(\App\test\MyUser::class);
var_dump($myuser->myredis->getValue());