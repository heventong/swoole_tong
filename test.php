<?php
require_once __DIR__."/vendor/autoload.php";




require_once __DIR__."/app/config/define.php"; //自定义配置
\Core\BeanFactory::init();//初始化Bean工厂

$router=\Core\BeanFactory::getBean("RouterCollector"); //从IoC容器中加载出 我们所需要的Bean

var_dump($router->routes);

