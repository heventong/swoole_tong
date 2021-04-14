<?php
namespace Core\init;
use Core\annotations\Bean;
use Core\lib\DBPool;


/**
 * Class PDOPool
 * @Bean()
 */
class PDOPool extends DBPool {

    public function __construct(int $min = 5, int $max = 10)
    {
        global $GLOBAL_CONFIGS;
        $poolconfig=$GLOBAL_CONFIGS["dbpool"]["default"];
        parent::__construct($poolconfig['min'], $poolconfig['max'],$poolconfig['idleTime']);
    }
    protected function newDB()
    {
        global $GLOBAL_CONFIGS;
        $default=$GLOBAL_CONFIGS["db"]["default"];
        $dsn="";
        {
            $driver=$default["driver"];
            $host=$default["host"];
            $dbname=$default['database'];
            $username=$default["username"];
            $password=$default["password"];
            $dsn="$driver:host=$host;dbname=$dbname";
        }
        //  $dsn="mysql:host=192.168.29.1;dbname=test";
        $pdo=new \PDO($dsn,$username,$password);
        return $pdo;
    }
}
