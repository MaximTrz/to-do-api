<?php


namespace System\Models;


use mysql_xdevapi\Exception;
use System\Contracts\ActiveRecordInterface;
use System\Contracts\HasQueryResult;
use System\Contracts\Queryable;
use System\DB\QueryBuilder;

abstract class Model implements ActiveRecordInterface
{
    const TABLE = '';

    public $id;

    protected static $db = null;
    public static $queryBuilder = null;

    static public function getTableName() : string
    {
        return static::TABLE;
    }

    public static function setDb(Queryable $db) : void
    {
        static::$db = $db;
    }

    public static function setQueryBuilder(QueryBuilder $queryBuilder) : void
    {
        static::$queryBuilder = $queryBuilder;
    }

    private static function checkDb() : void
    {
        if ( !(isset(static::$db) || isset(static::$queryBuilder)) ) {
            throw new Exception("Не установлены необходимые зависимости");
        }
    }


    static public function findAll() : mixed
    {
        static::checkDb();
        $sql = static::$queryBuilder->select("*")->from(static::getTableName())->where('ACTIVE_TO', '>',  date('Y-m-d H:i:s'))->getQuery();
        return static::$db->query($sql, static::class);
    }

    static public function findById($id) : mixed
    {
        static::checkDb();

        $sql = static::$queryBuilder->select("*")->from(static::getTableName())->where( 'ACTIVE_TO', '>',  date('Y-m-d H:i:s'))
            ->where("id","=",$id)
            ->getQuery();

        $result = static::$db->query($sql, static::class);

        return !empty($result)  ? $result[0] : null;

    }

    public function isNew() : bool
    {
        return empty($this->id);
    }

    public function insert() : HasQueryResult
    {

        static::checkDb();

        $fields = get_object_vars($this);

        unset($fields["id"]);

        $sql = static::$queryBuilder->insert(static::getTableName(), $fields)->getQuery();

        return static::$db->execute($sql);

    }

    public function update()
    {

        static::checkDb();

        static::$queryBuilder->update(static::getTableName());

        foreach (get_object_vars($this) as $key => $value) {
            static::$queryBuilder->set($key, $value);
        }

        static::$queryBuilder->where('id', '=', $this->id);

        $sql = static::$queryBuilder->getQuery();

        return static::$db->execute($sql);

    }

    public function save() : HasQueryResult
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

        static::checkDb();

        static::$queryBuilder->update(static::getTableName());
        static::$queryBuilder->set("active_to", date('Y-m-d H:i:s'));
        static::$queryBuilder->where('id', '=', $this->id);

        $sql = static::$queryBuilder->getQuery();

        return static::$db->execute($sql);

    }

}