<?php


namespace System\Contracts;


interface QueryBuilderInterface
{
    public function select(array|string ...$fields);

    public function from(string $table);

    public function where(string $field, string $operator, string|int $value);

    public function getQuery();
}