<?php
namespace Core\annotations;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Model{
    public $name="";
}