<?php
require __DIR__ . '/autoload.php';

try {
    require_once (__DIR__."/init.php");
    require_once (__DIR__."/App/routes.php");
} catch (\System\Exceptions\DB $exception){
    echo $exception->getMessage();
}
catch (\System\Exceptions\RouteException $exception){
    http_response_code(404);
    echo $exception->getMessage();
}
catch (Exception $exception){
    echo $exception->getMessage();
} finally {
    // todo Показ какой нибудь страницы
}