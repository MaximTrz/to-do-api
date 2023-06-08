<?php

namespace System\Traits;

trait Singletone
{

    protected static $instance;

    private function __construct()
    {
    }

    static public function getInstace()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

}