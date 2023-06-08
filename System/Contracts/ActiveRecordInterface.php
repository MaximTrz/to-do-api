<?php


namespace System\Contracts;


interface ActiveRecordInterface
{
    static public function findAll();
    static public function findById($id);
    public function isNew();
    public function insert();
    public function update();
    public function delete();
    public function save();
}