<?php
require __DIR__ . '/autoload.php';


$config = \App\Config::getInstace();

$logger = new System\Logger($config->data["log"]["DBLog"]);

$db = System\DB\Db::getInstace($config, $logger);

$builder = System\DB\QueryBuilder::getInstace();

\App\Models\Book::setDb($db);
\App\Models\Book::setQueryBuilder($builder);

$book = \App\Models\Book::findById(1);

var_dump($book);

$router = new \Bramus\Router\Router();