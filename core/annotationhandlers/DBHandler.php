<?php
namespace Core\annotationhandlers;

use Core\annotations\DB;
use Core\BeanFactory;
use Core\init\MyDB;

return [
    DB::class=>function(\ReflectionProperty $prop,$instance,$self){
        $mydb_bean=BeanFactory::getBean(MyDB::class);
        $prop->setAccessible(true);
        $prop->setValue($instance,$mydb_bean);
        return $instance;
    }
];