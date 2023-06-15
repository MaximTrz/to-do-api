<?php


namespace System\Contracts;


interface HasActions
{
    public function action(string $action, Array|int $params = null);
}