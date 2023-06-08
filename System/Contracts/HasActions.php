<?php


namespace App\Controllers\Interfaces;


interface HasActions
{
    public function action($action, $params = []);
}