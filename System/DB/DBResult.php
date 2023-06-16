<?php


namespace System\DB;


use System\Contracts\HasQueryResult;

class DBResult implements HasQueryResult
{
    private bool $result;
    private int|null $id;

    public function __construct(bool $result, int|null $id = null)
    {
        $this->result = $result;
        $this->id = intval($id);
    }

    public function getQueryResult() : bool
    {
        return $this->result;
    }

    public function getId() : int|null
    {
        return $this->id;
    }

}