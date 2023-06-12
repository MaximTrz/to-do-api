<?php


namespace System\Contracts;


interface QueryBuilderInterface
{
    public function select(array|string ...$fields);

    public function from(string $table);

    public function where(string $field, string $operator, string|int $value);

    public function getQuery();

    public function set(string $field, string|int $value);

    public function insert(string $table, array $values);

    public function update(string $table);

    public function delete(string $table);

    public function limit(int $limit);

}