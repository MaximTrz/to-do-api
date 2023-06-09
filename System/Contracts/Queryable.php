<?php


namespace System\Contracts;

interface Queryable
{
    public function query(string $sql, string $class = null, array $params = []);
}