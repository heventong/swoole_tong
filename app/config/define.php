<?php

define("ROOT_PATH",dirname(dirname(__DIR__)));
$GLOBAL_CONFIGS=[
    "db"=>require_once(__DIR__."/db.php"),
    "mq"=>require_once(__DIR__."/mq.php"),
];