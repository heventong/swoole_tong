<?php
namespace Core\annotationhandlers;

use Core\annotations\DB;
use Core\BeanFactory;
use Core\init\MyDB;

return [
    DB::class=>function(\ReflectionProperty $prop,$instance,$self){
        $mydb_bean=null;
        if($self->source!=="default"){
            $bean_name=MyDB::class."_".$self->source;
            $mydb_bean=BeanFactory::getBean($bean_name);
            if(!$mydb_bean) {
                $mydb_bean = clone BeanFactory::getBean(MyDB::class);//复制一个mydb对象
                $mydb_bean->setDbSource($self->source);
                BeanFactory::setBean($bean_name, $mydb_bean);
            }
        }else
            $mydb_bean=BeanFactory::getBean(MyDB::class);

        $prop->setAccessible(true);
        $prop->setValue($instance,$mydb_bean);
        return $instance;
    }
];