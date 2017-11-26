<?php

define("PROJECT_NAME","Framework");

define("HOST","192.168.10.10");
define("DB_NAME","framework");
define("USERNAME","homestead");
define("PASSWORD","secret");


define("VIEW_PATH", __DIR__ . "/../../views/");


$_SESSION['errors'] = [];
$_SESSION['flashdata'] = [];


$BASE_DB_CONNECTION = new \PDO("mysql:host=". HOST .";dbname=".DB_NAME,USERNAME,PASSWORD);
