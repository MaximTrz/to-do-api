<?php

namespace System\DB;

use App\Config;
use mysql_xdevapi\Exception;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use System\Contracts\ActiveRecordInterface;
use System\Contracts\Queryable;
use System\Models\Model;
use System\Traits\Singletone;


class Db
    implements LoggerAwareInterface, Queryable {

    private $logger;
    private $config;

    use Singletone;

    protected function __construct(Config $config, AbstractLogger $logger)
    {

        $this->config = $config;

        $this->setLogger($logger);

        try {
            $this->dbh = new \PDO('mysql:host=' . $this->config->data['db']['host'] . ';'
                . 'dbname=' . $this->config->data['db']['dbname'],
                $this->config->data['db']['user'], $this->config->data['db']['password']);

        } catch (\PDOException $ex) {
            $this->logger->log('Error', '{date}, Не удалось установить соединение с БД. Имя базы: {DB}, Имя пользователя: {user}, ', $this->getContext());
            throw new \Exception\DB('Ошибка подключения к БД');

        }

    }

    static public function getInstace(Config $config = null, AbstractLogger $logger = null){
        if (null === static::$instance) {
            static::$instance = new static($config, $logger);
        }
        return static::$instance;
    }

    public function execute($sql, $params = [])
    {
        $sth = $this->dbh->prepare($sql);
        $res = $sth->execute($params);

        if (!$res){
            $this->logger->log('Error', '{date}, Не удалось выполнить запрос БД. Имя базы: {DB}, Текст запроса: {SQL}, POST: {POST}',
            $this->getContext($sql));
        }

        return ['result' => $res, 'id' => $this->dbh->lastInsertId()];
    }

    /**
     * @param $sql Запрос
     * @param $class Класс вовращаемых данных
     * @param array $params Параметры для подстановки в запрос
     * @return array Маасив с результатом выполненного запроса
     */
    public function query(string $sql, string $class = null, array $params = [])
    {

        $sth = $this->dbh->prepare($sql);
        $res = $sth->execute($params);

        if ($res || (isset($class))) {
            return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
        }

        if ($res) {
            return $res;
        }

        $this->logger->log('warning', '{date}, Не удалось выполнить запрос БД. Имя базы: {DB}, Текст запроса: {SQL}}, ', $this->getContext());
        return $res;
    }

    private function getContext(string $sql = null){
        return [
            'DB'=>$this->config->data['db']['dbname'],
            'user' => $this->config->data['db']['user'],
            'SQL'=>$sql,
            'POST'=>implode('; ', $_POST)
        ];
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}