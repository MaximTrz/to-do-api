<?php

namespace App;
/**
 * Class Config
 * @package App
 * Конфигурация приложения
 */
class Config
{
    use \App\Singletone;

    /**
     * @var \string[][]
     * массив конфигурации приложения
     */
    public $data = [
        'db' => [
            'host' => '127.0.0.1',
            'dbname' => 'books',
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
        'imagesCatalogName' => [
            'history' => 'history',
            'today' => 'today'
        ]
    ];

}