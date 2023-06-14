<?php
require __DIR__ . '/autoload.php';


$config = \App\Config::getInstace();

$logger = new System\Logger($config->data["log"]["DBLog"]);

$db = System\DB\Db::getInstace($config, $logger);

$builder = new \System\DB\QueryBuilder();

//\App\Models\Element::setDb($db);
//\App\Models\Element::setQueryBuilder($builder);



//$controller = new App\Controllers\Api\Task(new \App\Models\Element(), new \System\Views\View() );
//
//$controller->action("get", 1);

$router = new \Bramus\Router\Router();