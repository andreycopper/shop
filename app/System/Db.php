<?php

namespace System;

use Traits\Singleton;
use Exceptions\DbException;

/**
 * Class Db
 * @package App\System
 */
class Db
{
    protected $dbh;

    use Singleton;

    /**
     * @throws DbException
     */
    public function __construct()
    {
        $config = Config::getInstance()->data;
        $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}";
        try {
            $this->dbh = new \PDO(
                $dsn,
                $config['db']['user'],
                $config['db']['password'],
                [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                ]
            );
        }
        catch (\PDOException $e) {
            throw new DbException($e->getMessage(), $e->getCode());
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
            throw new DbException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Выполняет запрос к БД и извлекает данные из запроса
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
            throw new DbException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Выполняет запрос к БД. Генерирует запись за записью из ответа сервера базы данных.
     * Не делает fetchAll(), а построчно выполняет fetch()
     * @param string $sql
     * @param array $params
     * @param string|null $class
     * @return \Generator
     * @throws DbException
     */
    public function queryEach(string $sql, array $params = [], string $class = null)
    {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($params);
            $class ? $sth->setFetchMode(\PDO::FETCH_CLASS, $class) : $sth->setFetchMode(\PDO::FETCH_ASSOC);
            while ($row = $sth->fetch()) yield $row;
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage(), $e->getCode());
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
