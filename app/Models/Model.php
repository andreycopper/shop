<?php

namespace Models;

use System\Config;
use System\Db;
use System\Logger;
use Traits\Magic;
use Traits\CastableToArray;
use Exceptions\DbException;
use Exceptions\UserException;

/**
 * Class Model
 * @package App\Models
 */
abstract class Model
{
    protected static $table = null;

    use Magic;
    use CastableToArray;

    /**
     * Создает объект вызвавшего класса и заполняет его свойства
     * @param Model $item
     * @return static
     */
    public static function factory(self $item)
    {
        $object = new static();
        foreach (get_class_vars(get_called_class()) as $key => $field) {
            if ('table' === $key) continue;
            $object->$key = $item->$key ?? null;
        }
        return $object;
    }

    /**
     * Находит и возвращает записи из БД
     * @param string $order
     * @param string $sort
     * @param bool $active
     * @param bool $object
     * @return array|bool
     * @throws DbException
     */
    public static function getList(string $order = 'created', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'WHERE active IS NOT NULL' : '';
        $sql = "SELECT * FROM " . static::$table . " {$activity} ORDER BY " . $order . " " . strtoupper($sort);
        $db = new Db();
        $data = $db->query($sql, [], $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Находит и возвращает одну запись из БД по id
     * @param int $id
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     * @throws DbException
     */
    public static function getById(int $id, bool $active = false, bool $object = true)
    {
        $where = !empty($active) ? ' AND active IS NOT NULL' : '';
        $sql = "SELECT * FROM " . static::$table . " WHERE id = :id {$where}";
        $params = [
            ':id' => $id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    public static function getByField(string $field, string $value, bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? ' AND active IS NOT NULL' : '';
        $sql = "SELECT * FROM `" . static::$table . "` WHERE {$field} = :value {$activity}";
        $params = [
            ':value' => $value
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Сохраняет запись в БД
     * @return bool|int
     * @throws DbException
     */
    public function save()
    {
        return $this->isNew() ? $this->insert() : $this->update();
    }

    /**
     * Проверяет добавляется новый элемент или редактируется существующий
     * @return bool
     * @throws DbException
     */
    public function isNew(): bool
    {
        return (!empty($this->id) && !empty(self::getById($this->id))) ? false : true;
    }

    /**
     * Добавляет запись в БД
     * @return bool|int
     * @throws DbException
     */
    public function insert()
    {
        $cols = [];
        $params = [];
        foreach ($this as $key => $val) {
            $cols[] = $key;
            $params[':' . $key] = $val;
        }
        $sql =  'INSERT INTO ' . static::$table . ' (' . implode(', ', $cols) . ') VALUES (' . ':' . implode(', :', $cols) . ')';
        $db = new Db();
        $res = $db->execute($sql, $params);
        return !empty($res) ? $db->lastInsertId() : false;
    }

    /**
     * Обновляет запись в БД
     * @throws DbException
     */
    public function update()
    {
        $binds = [];
        $params = [];
        foreach ($this as $key => $val) {
            if ('id' !== $key) {
                $binds[] = $key . '=:' . $key;
            }
            $params[':' . $key] = $val;
        }
        $sql = 'UPDATE ' . static::$table . ' SET ' . implode(', ', $binds) . ' WHERE id = :id';
        $db = new Db();
        return $db->execute($sql, $params) ? $this->id : false;
    }

    /**
     * Удаляет запись из БД
     * @return bool
     * @throws DbException
     */
    public function delete(): bool
    {
        $sql = 'DELETE FROM ' . static::$table . ' WHERE id = :id';
        $params = [
            ':id' => $this->id
        ];
        $db = new Db();
        return $db->execute($sql, $params);
    }

    /**
     * Возвращает количество записей в таблице
     * @return bool|int
     * @throws DbException
     */
    public static function count()
    {
        $sql = 'SELECT COUNT(*) as count FROM ' . static::$table;
        $db = new Db();
        $data = $db->query($sql, [], static::class);
        return !empty($data) ? (int)array_shift($data)->count : false;
    }

    /**
     * Заполняет поля модели данными из массива
     * Запускает метод обработки даного поля, если он существует
     * @param array $data
     * @return $this
     */
    public function fill(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'filter_' . mb_strtolower($key);
            if (method_exists($this, $method)) $value = $this->$method($value);
            if ('' === $value) $value = null;
            $this->$key = $value;
        }
        return $this;
    }
}
