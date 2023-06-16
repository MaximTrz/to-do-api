<?php


namespace System\Contracts;


interface HasQueryResult
{
    public function getQueryResult() : bool;

    public function getId() : mixed;

}