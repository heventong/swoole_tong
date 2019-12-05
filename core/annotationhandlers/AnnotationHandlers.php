<?php

namespace Core\annotationhandlers;

use Core\annotations\Value;
use Core\annotations\Bean;

return [

    Bean::class=>function($instance,$container,$self){
        $vars = get_object_vars($self);
        $beanName = '';
        if(isset($vars['name'])&&!empty($vars['name'])){
            $beanName = $vars['name'];
        }else{
            $arrs=explode("\\",get_class($instance));
            $beanName=end($arrs);
        }
        $container->set($beanName,$instance);
    },

    Value::class=>function(\ReflectionProperty $prop,$instance,$self){
        $env = parse_ini_file(ROOT_PATH.'/env');
        if(!isset($env[$self->name]) || $self->name == '') return $instance;
        $prop->setValue($instance,$env[$self->name]);
        return $instance;
    }
];