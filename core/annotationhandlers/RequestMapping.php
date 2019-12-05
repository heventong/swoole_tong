<?php
namespace Core\annotationhandlers;

use Core\annotations\RequestMapping;
use Core\BeanFactory;

return [
    RequestMapping::class=>function(\ReflectionMethod $method,$instance,$self){
        $path =$self->value;//uri
        $request_method =count($self->method)>0?$self->method:['GET'];
        $router_collector =BeanFactory::getBean('RouterCollector');

        $router_collector -> addRouter($request_method,$path,function()use($instance,$method){
            $method->invoke($instance);
        });
        return $instance;
    }
];