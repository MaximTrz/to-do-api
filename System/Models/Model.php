<?php


namespace App\Models;


use App\Db;
use App\Models\Interfaces\CRUD;

abstract class Model implements CRUD
{
    const TABLE = '';

    public $id;

    static public function getTableName(){
        return static::TABLE;
    }

    static public function findAll()
    {
        $db = Db::getInstace();
        $sql = 'SELECT * FROM ' . static::getTableName() . ' WHERE ACTIVE_TO>CURRENT_DATE';
        return $db->query($sql, static::class);
    }

    static public function findById($id)
    {

        $db = Db::getInstace();

        $sql = 'SELECT * FROM ' . static::getTableName(). ' WHERE ACTIVE_TO>CURRENT_DATE';

        return $db->query($sql, static::class, ['id' => $id])[0];
    }

    public function isNew()
    {
        return empty($this->id);
    }

    public function insert()
    {
        $columns = [];
        $values = [];

        foreach ($this as $key => $value) {

            if ('id' == $key) {
                continue;
            }

            if (empty($value)) {
                continue;
            }

            $columns[] = $key;
            $values[':' . $key] = $value;

        }

        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . implode(' ,', $columns) . ')' .
            ' VALUES (' . implode(', ', array_keys($values)) . ')';

        $db = Db::getInstace();
        $res = $db->execute($sql, $values);

        if (true == $res['result']) {
            $this->id = $res['id'];
        }
        return $res;
    }

    public function update()
    {

        $values = [];
        $set = '';

        $i = 0;

        foreach ($this as $key => $value) {
            if ('id' == $key) {
                continue;
            }

            if ($key == 'links'){
                $i++;
                continue;
            }

            $i++;
            $set .= ' ' . $key . '=' . ':' . $key;

            if (!($i == count(get_object_vars($this)) - 1)) {
                $set .= ', ';
            }

            $values[':' . $key] = $value;
        }

        $values[':id'] = $this->id;

        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . $set . ' WHERE id = :id';

        $db = Db::getInstace();

        return $db->execute($sql, $values);;

    }

    public function save()
    {

        if ($this->isNew() == true) {

            $res = $this->insert();

        } else {

            $res = $this->update();

        }

        return $res;

    }

    public function delete()
    {

        $sql = 'UPDATE ' . static::getTableName() . ' SET ACTIVE_TO = CURRENT_DATE' .  ' WHERE id = :id';
        $params = [':id' => $this->id];
        $db = Db::getInstace();
        $res = $db->execute($sql, $params);

        return $res;

    }

}