<?php
require __DIR__ . '/autoload.php';


$config = \App\Config::getInstace();

$logger = new System\Logger($config->data["log"]["DBLog"]);

$db = System\DB\Db::getInstace($config, $logger);

$builder = new \System\DB\QueryBuilder();

\App\Models\Element::setDb($db);
\App\Models\Element::setQueryBuilder($builder);

//$book = new \App\Models\Element();
//
//$book->name = "1111555";
//
//$sql = $book->insert();

//$element = \App\Models\Element::findById(1);

//$element->name = "Te11155";
//
////$element->update();
//
//var_dump($element->save());

$element = new \App\Models\Element();

$element->name = "Жопа";

$element->save();

$router = new \Bramus\Router\Router();