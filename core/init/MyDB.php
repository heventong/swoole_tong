<?php
namespace Core\init;
use Core\annotations\Bean;
use Illuminate\Database\Capsule\Manager as lvdb;

/**
 * @Bean
 * @method \Illuminate\Database\Query\Builder table(string  $table,string|null  $connection=null)
 */
class MyDB{
    private $lvDB;
    public function __construct()
    {
        global $GLOBAL_CONFIGS;
        //default 为默认数据源
        if(isset($GLOBAL_CONFIGS['db']) && isset($GLOBAL_CONFIGS['db']['default'])){
            $this->lvDB=new lvdb();
            $this->lvDB->addConnection($GLOBAL_CONFIGS['db']['default']);
            $this->lvDB->setAsGlobal();
            $this->lvDB->bootEloquent();
        }
    }
    public function __call($methodName, $arguments)
    {
        // $this->lvDB::table()
        return $this->lvDB::$methodName(...$arguments);
    }
}