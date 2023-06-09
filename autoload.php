<?php

spl_autoload_register(function($className)
{
    $path = __DIR__ . '/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($path)) {
        include str_replace('\\', '/', $path);
    }
});

$path = file_exists(__DIR__.'/vendor/autoload.php');
include str_replace('\\', '/', __DIR__.'/vendor/autoload.php');