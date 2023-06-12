<?php

namespace App;
use System\Traits\Singletone;

/**
 * Class Config
 * @package App
 * Конфигурация приложения
 */
class Config
{
    use Singletone;

    /**
     * @var \string[][]
     * массив конфигурации приложения
     */
    public $data = [
        'db' => [
            'host' => '127.0.0.1',
            'dbname' => 'kungur_glasses',
            'user' => 'root',
            'password' => 'root'
        ],
        'log' =>[
            'mainLog' => 'main.log',
            'DBLog' => 'DBLog.log'
        ],
        'templates' => [
            'path' => 'App/templates'
        ],
    ];

}