<?php
namespace Core\annotationhandlers;

use Core\annotations\MQ;
use Core\BeanFactory;
use Core\init\MyMQ;

return [
    MQ::class=>function(\ReflectionProperty $prop,$instance,$self){
        $mymq_bean=BeanFactory::getBean(MyMQ::class);
        $prop->setAccessible(true);
        $prop->setValue($instance,$mymq_bean);
        return $instance;
    }
];