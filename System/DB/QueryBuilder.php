<?php


namespace System\DB;


class QueryBuilder implements System\Contracts\QueryBuilderInterface
{

    use \System\Singletone;

    protected array $select = [];
    protected string $from;
    protected array $where = [];

    public function select(array|string ...$fields): self {
        $this->select = is_array($fields[0]) ? $fields[0] : $fields;
        return $this;
    }

    public function from(string $table): self {
        $this->from = $table;
        return $this;
    }

    public function where(string $field, string $operator, string|int $value): self {
        $this->where[] = "$field $operator '$value'";
        return $this;
    }

    public function getQuery(): string {
        $select = implode(', ', $this->select);
        $where = count($this->where) ? 'WHERE '.implode(' AND ', $this->where) : '';

        return "SELECT $select FROM {$this->from} $where";
    }

}