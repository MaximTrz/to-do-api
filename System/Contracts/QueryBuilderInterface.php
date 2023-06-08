<?php


namespace System\Contracts;


interface QueryBuilderInterface
{
    public function select($fields);

    public function from($table);

    public function where($field, $operator, $value);

    public function getQuery();
}