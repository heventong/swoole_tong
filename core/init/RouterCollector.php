<?php
namespace Core\init;
use Core\annotations\Bean;

/**
 * 路由收集器
 * @Bean()
 */
class  RouterCollector{
    public $routes=[];
    //收集路由
    public function  addRouter($method,$uri,$handler){
        $this->routes[]=['method'=>$method,'uri'=>$uri,"handler"=>$handler];
    }

    public function getDispatcher(){
        return \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route){
                $r->addRoute($route['method'], $route['uri'], $route['handler']);
            }

        });
    }
}