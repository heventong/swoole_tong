<?php
namespace Core\init;
use Core\annotations\Bean;
use DI\Annotation\Inject;
use Illuminate\Database\Capsule\Manager as lvdb;

/**
 * @Bean
 * @method \Illuminate\Database\Query\Builder table(string  $table,string|null  $connection=null)
 */
class MyDB{
    private $lvDB;
    private $dbSource="default";

    /**
     * @Inject()
     * @var PDOPool
     */
    public $pdopool;
    /**
     * @return string
     */
    public function getDbSource(): string
    {
        return $this->dbSource;
    }

    /**
     * @param string $dbSource
     */
    public function setDbSource(string $dbSource)
    {
        $this->dbSource = $dbSource;
    }
    public function __construct()
    {
        global $GLOBAL_CONFIGS;
        //default 为默认数据源
        if(isset($GLOBAL_CONFIGS['db'])){
            $configs=$GLOBAL_CONFIGS['db'];
            $this->lvDB=new lvdb();
            foreach ($configs as $key=>$value)
            {
                //  $this->lvDB->addConnection($value,$key);
                $this->lvDB->addConnection(["driver"=>"mysql"],$key);
            }

            $this->lvDB->setAsGlobal();
            $this->lvDB->bootEloquent();
        }

    }
    public function __call($methodName, $arguments)
    {
        $pdo_object=$this->pdopool->getConnection();
        try{
            if(!$pdo_object) return [];
            $this->lvDB->getConnection($this->dbSource)->setPdo($pdo_object->db);
            $ret=$this->lvDB::connection($this->dbSource)->$methodName(...$arguments);
            return $ret;
        }catch (\Exception $exception){
            return null;
        }
        finally{
            if($pdo_object)
                $this->pdopool->close($pdo_object); //放回连接
        }





        //  return   $this->lvDB::connection($this->dbSource)->$methodName(...$arguments);
    }
}