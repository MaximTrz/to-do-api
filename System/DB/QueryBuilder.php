<?php


namespace System\DB;

use System\Contracts\QueryBuilderInterface;
use System\Traits\Singletone;

class QueryBuilder implements QueryBuilderInterface
{
    public array $select = [];
    protected string $from;
    protected $insert = [];
    protected ?string $delete = null;
    protected array $where = [];
    protected array $set = [];
    protected string $table;
    protected ?string $limit = null;
    protected ?string $query = null;

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

    public function set(string $field, string|int $value): self {
        $this->set[] = "$field='$value'";
        return $this;
    }

    public function insert(string $table, array $values): self {
        $this->table = $table;
        $columns = implode(", ", array_keys($values));
        $data = "'".implode("', '", array_values($values))."'";
        $this->query = "INSERT INTO $table ($columns) VALUES ($data)";
        return $this;
    }

    public function update(string $table): self {
        $this->table = $table;
        return $this;
    }

    public function delete(string $table): self {
        $this->delete = $table;
        return $this;
    }

    public function limit(int $limit): self {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function getQuery(): string {

        if(!empty($this->select)) {
            $select = implode(', ', $this->select);
            $where = count($this->where) ? 'WHERE '.implode(' AND ', $this->where) : '';
            $this->select = [];
            $this->where = [];
            return "SELECT $select FROM {$this->from} $where $this->limit";
        }
        if(!empty($this->set)) {
            $set = implode(', ', $this->set);
            $where = count($this->where) ? 'WHERE '.implode(' AND ', $this->where) : '';
            $this->where = [];
            $this->set = [];
            return "UPDATE {$this->table} SET $set $where";
        }
        if(!empty($this->delete)) {
            $table = $this->delete;
            $where = count($this->where) ? 'WHERE '.implode(' AND ', $this->where) : '';
            $this->where = [];
            $this->delete = null;
            return "DELETE FROM {$table} $where";
        }


        if(!empty($this->insert)) {
            $columns = implode(', ', array_keys($this->insert));
            $values = implode(', ', array_map(function($value) { return "'$value'"; }, array_values($this->insert)));
            $this->insert = null;
            return "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        }

        if(!empty($this->query)) {
            return $this->query;
        } else {
            throw new Exception("No query found");
        }
    }
}