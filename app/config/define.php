<?php

define("ROOT_PATH",dirname(dirname(__DIR__)));
$GLOBAL_CONFIGS=[
    "db"=>require_once(__DIR__."/db.php"),
    "mq"=>require_once(__DIR__."/mq.php"),
    "redis"=>require_once(__DIR__."/redis.php"),
    "dbpool"=>require_once(__DIR__."/dbpool.php"),
];