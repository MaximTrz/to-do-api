<?php


namespace System\DB;

use System\Contracts\QueryBuilderInterface;
use System\Traits\Singletone;

class QueryBuilder implements QueryBuilderInterface
{
    use Singletone;

    protected array $select = [];
    protected string $from;
    protected array $where = [];
    protected array $set = [];
    protected string $table;
    protected ?string $limit = null;

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
        $this->table = $table;
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
            return "SELECT $select FROM {$this->from} $where $this->limit";
        } else if(!empty($this->set)) {
            $set = implode(', ', $this->set);
            $where = count($this->where) ? 'WHERE '.implode(' AND ', $this->where) : '';
            return "UPDATE {$this->table} SET $set $where";
        } else if(!empty($this->table) && empty($this->set)) {
            $where = count($this->where) ? 'WHERE '.implode(' AND ', $this->where) : '';
            return "DELETE FROM {$this->table} $where";
        } else if(!empty($this->table) && !empty($this->set)) {
            $set = implode(', ', $this->set);
            $where = count($this->where) ? 'WHERE '.implode(' AND ', $this->where) : '';
            return "UPDATE {$this->table} SET $set $where";
        } else if(!empty($this->table)) {
            return $this->query;
        } else {
            throw new Exception("No query found");
        }
    }
}
