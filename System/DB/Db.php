<?php

namespace System\DB;

use App\Config;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use System\Contracts\HasQueryResult;
use System\Contracts\Queryable;


class Db
    implements LoggerAwareInterface, Queryable {

    private $logger;
    private $config;
    private $resulType;

    public function __construct(Config $config, AbstractLogger $logger, string $resultType)
    {

        $this->config = $config;

        $this->setLogger($logger);

        $this->resulType = $resultType;

        try {
            $this->dbh = new \PDO('mysql:host=' . $this->config->data['db']['host'] . ';'
                . 'dbname=' . $this->config->data['db']['dbname'],
                $this->config->data['db']['user'], $this->config->data['db']['password']);

        } catch (\PDOException $ex) {
            $this->logger->log('Error', '{date}, Не удалось установить соединение с БД. Имя базы: {DB}, Имя пользователя: {user}, ', $this->getContext());
            throw new \Exception\DB('Ошибка подключения к БД');

        }

    }

    public function execute(string $sql, array $params = []): HasQueryResult
    {
        $sth = $this->dbh->prepare($sql);
        $res = $sth->execute($params);

        if (!$res){
            $this->logger->log('Error', '{date}, Не удалось выполнить запрос БД. Имя базы: {DB}, Текст запроса: {SQL}, POST: {POST}',
            $this->getContext($sql));
        }

        try {
            return new $this->resulType($res, $this->dbh->lastInsertId());;
        } catch (\Exception){
            throw new \Exception("Не удалось создать экземпляр класса DBResult");
        }

    }

    /**
     * @param string $sql Запрос
     * @param string|null $class Класс вовращаемых данных
     * @param array $params Параметры для подстановки в запрос
     * @return HasQueryResult Результат выполнения запроса
     */
    public function query(string $sql, string $class = null, array $params = [])
    {

        $sth = $this->dbh->prepare($sql);
        $res = $sth->execute($params);

        if ($res || (isset($class))) {
            return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
        }

        if (!$res->getResult) {
            $this->logger->log('warning', '{date}, Не удалось выполнить запрос БД. Имя базы: {DB}, Текст запроса: {SQL}}, ', $this->getContext());
        }

        return new $this->resulType($res);
    }

    private function getContext(string $sql = null)
    {
        return [
            'DB'=>$this->config->data['db']['dbname'],
            'user' => $this->config->data['db']['user'],
            'SQL'=>$sql,
            'POST'=>implode('; ', $_POST)
        ];
    }

    public function setLogger(LoggerInterface $logger) : void
    {
        $this->logger = $logger;
    }
}