<?php
namespace Core;

use App\controllers\TestController;
use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class BeanFactory{
    private static $env = [];//配置文件
    private static $cotainer;// ioc

    public static function init(){//初始化函数
        self::$env =parse_ini_file(ROOT_PATH.'/env');

        $builder = new ContainerBuilder();//初始化容器Builder
        $builder->useAnnotations(true);//启用注解，主要是用它的Inject注解
        self::$cotainer=$builder->build(); //容器初始化
        self::ScanBeans(); //扫描
    }
    private static function getEnv(string $key,string $default=""){ //获取env文件中的配置内容
        if(isset(self::$env[$key])) return self::$env[$key];
        return $default;
    }
    public static function getBean($name){
        return self::$cotainer->get($name);
    }
    public static function testBean(){
        return self::$cotainer->get(TestController::class);
    }
    public static function ScanBeans(){
        //读取注解 对应的handler
        $anno_handlers=require_once(ROOT_PATH."/core/annotations/AnnotationHandlers.php");

        $scan_dir=self::getEnv("scan_dir",ROOT_PATH."/app");//扫描路径
        $scan_root_namespace=self::getEnv("scan_root_namespace", "App\\");//扫描的 namespace
        $files=glob($scan_dir."/*.php");
        foreach ($files as $file){
            require_once $file;
        }
//        var_dump(get_declared_classes());
        $reader=new  AnnotationReader();
        AnnotationRegistry::registerAutoloadNamespace("Core\annotations");

        foreach (get_declared_classes() as $class){
            if(strstr($class,$scan_root_namespace)) {
                $ref_class=new \ReflectionClass($class);//目标类的反射对象
                $class_annos=$reader->getClassAnnotations($ref_class);//获取所有类注解
                /////下方是处理 类注解
                foreach ($class_annos as $class_anno){
                    $handler=$anno_handlers[get_class($class_anno)]; //获取handler处理过程
                    $instance = self::$cotainer->get($ref_class->getName());
                    //处理属性注解

                    //处理方法注解

                    //处理类注解
                    $handler($instance,self::$cotainer,$class_anno); //执行处理
                }
            }
        }
    }
}