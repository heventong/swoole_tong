<?php
namespace Core;
use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class BeanFactory{
    private static $env=[]; //env 配置文件
    private static $cotainer; //ioc 容器
    private static $handlers=[];
    public static function init() //初始化函数
    {
        self::$env=parse_ini_file(ROOT_PATH."/env");

        $builder=new ContainerBuilder(); //初始化容器Builder
        $builder->useAnnotations(true); //启用注解，主要是用它的Inject注解
        self::$cotainer=$builder->build(); //容器初始化

        $handlers=glob(ROOT_PATH . "/core/annotationhandlers/*.php");
        foreach ($handlers as $handler){
            self::$handlers=array_merge(self::$handlers,require_once($handler));
        }


        //设置注解加载类
        $loader = require __DIR__ . '/../vendor/autoload.php';
        AnnotationRegistry::registerLoader([$loader, 'loadClass']);


        $scans=[
            ROOT_PATH."/core/init"=>"Core\\", //先扫描框架 内部必须要扫描的 文件夹
            self::getEnv("scan_dir",ROOT_PATH."/app")=>self::getEnv("scan_root_namespace", "App\\")
        ];
        foreach ($scans as  $scan_dir=>$namespace){
            self::ScanBeans($scan_dir,$namespace); //扫描
        }




    }

    private static function getEnv(string $key,string $default=""){ //获取env文件中的配置内容
        if(isset(self::$env[$key])) return self::$env[$key];
        return $default;
    }
    public static function getBean($name){
        return self::$cotainer->get($name);
    }
    public static function ScanBeans($scan_dir,$scan_root_namespace){


        $files=glob($scan_dir."/*.php");
        foreach ($files as $file){
            require_once $file;
        }

        $reader=new  AnnotationReader();


        foreach (get_declared_classes() as $class){
            if(strstr($class,$scan_root_namespace)) {
                $ref_class=new \ReflectionClass($class);//目标类的反射对象
                $class_annos=$reader->getClassAnnotations($ref_class);//获取所有类注解
                /////下方是处理 类注解
                foreach ($class_annos as $class_anno){

                    $handler=self::$handlers[get_class($class_anno)]; //获取handler处理过程
                    $instance=self::$cotainer->get($ref_class->getName());
                    //处理属性注解
                    self::handlerPropAnno($instance,$ref_class,$reader);
                    //处理方法注解
                    self::handlerMethodAnno($instance,$ref_class,$reader);
                    ///
                    $handler($instance,self::$cotainer,$class_anno); //执行类注解处理
                }
            }
        }
    }
    //处理属性注解
    private static function handlerPropAnno(&$instance,\ReflectionClass $ref_class,AnnotationReader $reader){
        $props=$ref_class->getProperties();//取出反射对象所有属性
        foreach($props as $prop){
            $prop_annos=$reader->getPropertyAnnotations($prop);
            foreach ($prop_annos as $prop_anno){
                $handler=self::$handlers[get_class($prop_anno)];
                $instance=$handler($prop,$instance,$prop_anno);//处理属性注解
            }
        }
    }
    //处理方法注解
    private static function handlerMethodAnno(&$instance,\ReflectionClass $ref_class,AnnotationReader $reader){
        $methods=$ref_class->getMethods();//取出反射对象所有方法
        foreach($methods as $method){
            $method_annos=$reader->getMethodAnnotations($method);
            foreach ($method_annos as $method_anno){
                $handler=self::$handlers[get_class($method_anno)];
                $instance=$handler($method,$instance,$method_anno);//处理方法注解
            }
        }
    }



}