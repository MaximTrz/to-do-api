<?php


namespace System;

use App\Controllers\Api\Task as TaskController;
use App\Models\Task as TaskModel;
use System\Views\View;

class Container
{
    private array $objects = [];

    public function __construct()
    {
        $this->objects = [
            'db' => fn() => DB\Db::getInstace(),
            'model.task' => fn() => new TaskModel(),
            'controller.task' => fn() => new TaskController(new TaskModel(), new View()),
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