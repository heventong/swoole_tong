<?php
namespace App\core;
use App\annotations\Bean;
use Doctrine\Common\Annotations\AnnotationReader;

class BeanFactory{
    private static $beans = [];
    public static function ScanBeans(string $path,string $namespace){
        $phpfiles = glob($path."/*.php");
        foreach ($phpfiles as $php){
            require($php);
        }
        $classes = get_declared_classes();
        $reader = new AnnotationReader();
        foreach ($classes as $class){
            if(strstr($class,$namespace)){
                $ref_class = new \ReflectionClass($class);
                $class_annos =$reader->getClassAnnotations($ref_class);
                foreach ($class_annos as $class_anno){
                    if($class_anno instanceof Bean){
                        self::$beans[$ref_class->getName()] = self::loadClass($ref_class->getName(),$ref_class->newInstance());
//                        var_dump(self::$beans);
                    }
                }
            }
        }
    }
    public static function getBean(string $beanName){
        if(isset(self::$beans[$beanName]))
            return self::$beans[$beanName];
        return false;
    }
    public static function loadClass($className,$object=false){
        $ref_class = new \ReflectionClass($className);
//        return $ref_class->newInstance();
        $properties = $ref_class->getProperties();
        $reader = new AnnotationReader();
        foreach ($properties as $i =>$property){
            $annos = $reader->getPropertyAnnotations($property);
            foreach ($annos as $j =>$anno){
                $getValue  =$anno->do();
                $retObj = $object?$object:$ref_class->newInstance();
                $property->setValue($retObj,$getValue);
                return $retObj;
//                var_dump($anno);
            }
        }
        return $object?$object:$ref_class->newInstance();

    }
}