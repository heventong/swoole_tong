<?php
namespace Core\annotationhandlers;

use Core\annotations\RequestMapping;
use Core\BeanFactory;

return [
    RequestMapping::class=>function(\ReflectionMethod $method,$instance,$self){
        $path =$self->value;//uri
        $request_method =count($self->method)>0?$self->method:['GET'];
        $router_collector =BeanFactory::getBean('RouterCollector');

        $router_collector -> addRouter($request_method,$path,function($params,$ext_params)use($instance,$method){

            $inputParams=[];
            $ref_params=$method->getParameters();//得到方法的反射参数
            foreach ($ref_params as $ref_param){
                if(isset($params[$ref_param->getName()])){
                    $inputParams[]=$params[$ref_param->getName()];
                }else
                {
                    foreach ($ext_params as $ext_param){//$ext_param 都是实例对象，譬如request response,xxx,,判断累心
                        if($ref_param->getClass() && $ref_param->getClass()->isInstance($ext_param)){
                            $inputParams[]=$ext_param;
                            goto  end;
                        }
                    }
                    $inputParams[]=false;
                }
                end:
            }
            // return $method->invoke($instance);//执行反射方法
            return $method->invokeArgs($instance,$inputParams);

        });
        return $instance;
    }
];