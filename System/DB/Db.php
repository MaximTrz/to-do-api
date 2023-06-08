<?php

namespace System\DB;

use mysql_xdevapi\Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Db
 * @package App
 * Класс для работы с БД
 */
class Db
    implements LoggerAwareInterface{

    use \System\Singletone;

    protected $logger;

    protected function __construct()
    {

        $config = Config::getInstace();

        /// Установка логера
        $this->setLogger(new Logger( ($config->data['log']['DBLog'])));

        try {
            $this->dbh = new \PDO('mysql:host=' . $config->data['db']['host'] . ';' . 'dbname=' . $config->data['db']['dbname'], $config->data['db']['user'], $config->data['db']['password']);
        } catch (\PDOException $ex) {

            $context = ['DB'=>$config->data['db']['dbname'],
                        'UserDB'=>$config->data['db']['user']
            ];
            $this->logger->log('Error', '{date}, Не удалось установить соединение с БД. Имя базы: {DB}, Имя пользователя: {UserDB}}, ', $context);
            throw new \Exception\DB('Ошибка подключения к БД');

        }

    }

    /**
     * @param $sql Заспрос
     * @param array $params Параметры для подстановки в запрос
     * @return array    Результат запроса и ID вставленной записи
     * Выполенение запроса без получения данных
     */
    public function execute($sql, $params = [])
    {
        $sth = $this->dbh->prepare($sql);
        $res = $sth->execute($params);

        if (false == $res){
            $date = date("d-m-Y H:i:s");
            $config = Config::getInstace();

            $context = [
                        'DB'=>$config->data['db']['dbname'],
                        'date'=>$date,
                        'SQL'=>$sql,
                        'POST'=>implode('; ', $_POST)
                       ];

            $this->logger->log('Error', '{date}, Не удалось выполнить запрос БД. Имя базы: {DB}, Текст запроса: {SQL}, POST: {POST}', $context);
        }

        return ['result' => $res, 'id' => $this->dbh->lastInsertId()];
    }

    /**
     * @param $sql Запрос
     * @param $class Класс вовращаемых данных
     * @param array $params Параметры для подстановки в запрос
     * @return array Маасив с результатом выполненного запроса
     */
    public function query($sql, $class, $params = [])
    {

        $sth = $this->dbh->prepare($sql);
        $res = $sth->execute($params);

        if (!false == $res) {
            return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
        }

        $config = Config::getInstace();

        $context = [
            'DB'=>$config->data['db']['dbname'],
            'SQL'=>$sql
        ];

        $this->logger->log('warning', '{date}, Не удалось выполнить запрос БД. Имя базы: {DB}, Текст запроса: {SQL}}, ', $context);
        return [];
    }

    /**
     * @param LoggerInterface $logger
     * Установка логгера
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}