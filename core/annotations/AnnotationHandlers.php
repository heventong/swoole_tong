<?php

namespace Core\annotations;

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

    Value::class=>function(){

    }
];