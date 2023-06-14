<?php


namespace System\Contracts;


interface HasActions
{
    public function action($action, $params = []);
}