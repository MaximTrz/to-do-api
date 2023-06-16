<?php


namespace System;

use App\Config;

use App\Models\Task as TaskModel;
use System\Controllers\ControllerAPI;
use System\DB\Db;
use System\DB\DBResult;
use System\DB\QueryBuilder;
use System\Traits\Singletone;
use System\Views\View;

class Container
{

    use Singletone;

    private array $objects = [];

    protected function __construct()
    {
        $this->objects = [
            'db' => fn() => new Db(Config::getInstace(),
                                    new Logger(Config::getInstace()->data["log"]["DBLog"]),
                                    DBResult::class),
            'queryBuilder' => fn() => new QueryBuilder(),
            'logger' => fn() => new Logger(Config::getInstace()->data["log"]["mainLog"]),
            'model.task' => fn() => new TaskModel(),
            'controller.task' => fn() => new ControllerAPI(new TaskModel(), new View()),
        ];
    }

    public function has(string $id): bool
    {
        return isset($this->objects[$id]) || class_exists($id);
    }

    public function get(string $id): mixed
    {
        return
            isset($this->objects[$id]) ? $this->objects[$id]() : $this->prepareObject($id);
    }

    private function prepareObject(string $class): object
    {
        $classReflector = new \ReflectionClass($class);

        // Получаем рефлектор конструктора класса, проверяем - есть ли конструктор
        // Если конструктора нет - сразу возвращаем экземпляр класса
        $constructReflector = $classReflector->getConstructor();

        if (empty($constructReflector)) {
            return new $class;
        }

        // Получаем рефлекторы аргументов конструктора
        // Если аргументов нет - сразу возвращаем экземпляр класса
        $constructArguments = $constructReflector->getParameters();
        if (empty($constructArguments)) {
            return new $class;
        }

        // Перебираем все аргументы конструктора, собираем их значения
        $args = [];
        foreach ($constructArguments as $argument) {
            // Получаем тип аргумента
            $argumentType = $argument->getType()->getName();
            // Получаем сам аргумент по его типу из контейнера
            $args[$argument->getName()] = $this->get($argumentType);
        }

        // И возвращаем экземпляр класса со всеми зависимостями
        return new $class(...$args);
    }

}