<?php


namespace System\Models;


use System\Contracts\ActiveRecordInterface;
use System\Contracts\Queryable;
use System\DB\QueryBuilder;

abstract class Model implements ActiveRecordInterface
{
    const TABLE = '';

    public $id;

    protected static $db = null;
    public static $queryBuilder = null;

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

    private static function checkDb()
    {
        return isset(static::$db) || isset(static::$queryBuilder);
    }


    static public function findAll()
    {
        if (!static::checkDb()){
            return false;
        }
        $sql = static::$queryBuilder->select("*")->from(static::getTableName())->where('ACTIVE_TO', '>',  date('Y-m-d H:i:s'))->getQuery();
        return static::$db->query($sql, static::class);
    }

    static public function findById($id)
    {
        if (!static::checkDb()){
            return false;
        }
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

        if (!static::checkDb())
        {
            return false;
        }

        $fields = get_object_vars($this);

        unset($fields["id"]);

        $sql = static::$queryBuilder->insert(static::getTableName(), $fields)->getQuery();

        return static::$db->execute($sql);

    }

    public function update()
    {

        static::$queryBuilder->update(static::getTableName());

        foreach (get_object_vars($this) as $key => $value) {
            static::$queryBuilder->set($key, $value);
        }

        static::$queryBuilder->where('id', '=', $this->id);

        $sql = static::$queryBuilder->getQuery();

        return static::$db->execute($sql);

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