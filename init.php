<?php
require __DIR__ . '/autoload.php';


$config = \App\Config::getInstace();

$logger = new System\Logger($config->data["log"]["DBLog"]);

$db = System\DB\Db::getInstace($config, $logger);

$builder = new \System\DB\QueryBuilder();

\App\Models\Task::setDb($db);
\App\Models\Task::setQueryBuilder($builder);


$container = new \System\Container();

$model = \App\Models\Task::findById(1);

$router = new \Bramus\Router\Router();