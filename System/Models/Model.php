<?php


namespace System\Models;


use System\Contracts\ActiveRecordInterface;
use System\Contracts\Queryable;
use System\DB\QueryBuilder;

abstract class Model implements ActiveRecordInterface
{
    const TABLE = '';

    public $id;

    protected static $db;
    protected static $queryBuilder;

    static public function getTableName(){
        return static::TABLE;
    }

    public static function setDb(Queryable $db)
    {
        static::$db = $db;
    }

    public static function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        static::$queryBuilder = $queryBuilder;
    }

    static public function findAll()
    {
        $sql = static::$queryBuilder->select("*")->from(static::getTableName())->where('ACTIVE_TO', '>',  date('Y-m-d H:i:s'))->getQuery();
        return static::$db->query($sql, static::class);
    }

    static public function findById($id)
    {

        $sql = static::$queryBuilder->select("*")->from(static::getTableName())->where( 'ACTIVE_TO', '>',  date('Y-m-d H:i:s'))
            ->where("id","=",$id)
            ->getQuery();

        return static::$db->query($sql, static::class)[0];
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