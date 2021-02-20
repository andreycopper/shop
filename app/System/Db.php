<?php

namespace System;

use Exceptions\DbException;

/**
 * Class Db
 * @package App\System
 */
class Db
{
    protected $dbh;

    /**
     * Db constructor.
     * @throws DbException
     */
    public function __construct()
    {
        $config = Config::getInstance()->data;
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'];
        try {
            $this->dbh = new \PDO($dsn, $config['db']['user'], $config['db']['password'],[\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'']);
        } catch (\PDOException $e) {
            $exc = new DbException($e->getMessage(), $e->getCode());
            Logger::getInstance()->emergency($exc);
            throw $exc;
        }
    }

    /**
     * Выполняет запрос к БД
     * @param string $sql
     * @param string|null $class
     * @param array $params
     * @return array
     * @throws DbException
     */
    public function query(string $sql, array $params = [], string $class = null)
    {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($params);
            return $class ?
                $sth->fetchAll(\PDO::FETCH_CLASS, $class) :
                $sth->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e) {
            $exc = new DbException($e->getMessage(), $e->getCode());
            Logger::getInstance()->error($exc);
            throw $exc;
        }
    }

    /**
     * Выполняет запрос к БД. Генерирует запись за записью из ответа сервера базы данных.
     * Не делает fetchAll(), а построчно выполняет fetch()
     * @param string $sql
     * @param string $class
     * @param array $params
     * @return \Generator
     * @throws DbException
     */
    public function queryEach(string $sql, string $class = null, array $params = [])
    {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($params);
            $class ? $sth->setFetchMode(\PDO::FETCH_CLASS, $class) : $sth->setFetchMode(\PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) yield $row;
        } catch (\PDOException $e) {
            $exc = new DbException($e->getMessage(), $e->getCode());
            Logger::getInstance()->error($exc);
            throw $exc;
        }
    }

    /**
     * Выполняет запрос к БД
     * @param string $sql
     * @param array $params
     * @return bool
     * @throws DbException
     */
    public function execute(string $sql, array $params = []): bool
    {
        try {
            $sth = $this->dbh->prepare($sql);
            return $sth->execute($params);
        } catch (\PDOException $e) {
            $exc = new DbException($e->getMessage(), $e->getCode());
            Logger::getInstance()->error($exc);
            throw $exc;
        }
    }

    /**
     * Возвращает последний вставленный в БД id
     * @return int
     */
    public function lastInsertId(): int
    {
        return $this->dbh->lastInsertId();
    }
}
