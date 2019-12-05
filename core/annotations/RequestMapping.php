<?php
namespace Core\annotations;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class RequestMapping{
    public $value=""; //路径 如/test
    public $method=[]; //GET、POST等
}
