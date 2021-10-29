<?php

namespace Models;

use System\Db;
use Traits\Magic;
use Traits\CastableToArray;

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
     * @return Model|null
     */
    public static function factory(self $item)
    {
        if (!empty($item) && is_object($item)) {
            $object = new static();
            foreach (get_class_vars(get_called_class()) as $key => $field) {
                if ($key === 'table') continue;
                if (empty($item->$key)) continue;

                $object->$key = $item->$key ?? null;
            }
        }
        return $object ?? null;
    }

    /**
     * Находит и возвращает записи из БД
     * @param string $order
     * @param string $sort
     * @param bool $active
     * @param bool $object
     * @return array|bool
     */
    public static function getList(string $order = 'created', string $sort = 'ASC', bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'WHERE active IS NOT NULL' : '';
        $sql = "SELECT * FROM " . static::$table . " {$activity} ORDER BY {$order} " . strtoupper($sort);
        $db = Db::getInstance();
        $data = $db->query($sql, [], $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Находит и возвращает одну запись из БД по id
     * @param int $id
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     */
    public static function getById(int $id, bool $active = true, bool $object = true)
    {
        $where = !empty($active) ? ' AND active IS NOT NULL' : '';
        $sql = "SELECT * FROM " . static::$table . " WHERE id = :id {$where}";
        $params = [
            'id' => $id
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Находит и возвращает одну запись из БД по полю и его значению
     * @param string $field
     * @param string $value
     * @param bool $active
     * @param bool $object
     * @return array|false
     */
    public static function getByField(string $field, string $value, bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? ' AND active IS NOT NULL' : '';
        $sql = "SELECT * FROM `" . static::$table . "` WHERE {$field} = :value {$activity}";
        $params = [
            'value' => $value
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Сохраняет запись в БД
     * @return bool|int
     */
    public function save()
    {
        return $this->isNew() ? $this->insert() : $this->update();
    }

    /**
     * Проверяет добавляется новый элемент или редактируется существующий
     * @return bool
     */
    public function isNew(): bool
    {
        return !(!empty($this->id) && !empty(self::getById($this->id, false)));
    }

    /**
     * Добавляет запись в БД
     * @return bool|int
     */
    public function insert()
    {
        $cols = [];
        $params = [];
        foreach ($this as $key => $val) {
            if ($val === null) continue;
            $cols[] = $key;
            $params[':' . $key] = $val;
        }
        $sql =  'INSERT INTO ' . static::$table . ' (' . implode(', ', $cols) . ') VALUES (' . ':' . implode(', :', $cols) . ')';
        $db = Db::getInstance();
        $res = $db->execute($sql, $params);
        return !empty($res) ? $db->lastInsertId() : false;
    }

    /**
     * Обновляет запись в БД
     */
    public function update()
    {
        $binds = [];
        $params = [];
        foreach ($this as $key => $val) {
            if ($val === null) continue;
            if ('id' !== $key) $binds[] = $key . ' = :' . $key;
            $params[$key] = $val;
        }
        $sql = 'UPDATE ' . static::$table . ' SET ' . implode(', ', $binds) . ' WHERE id = :id';
        $db = Db::getInstance();
        return $db->execute($sql, $params) ? $this->id : false;
    }

    /**
     * Удаляет запись из БД
     * @return bool
     */
    public function delete(): bool
    {
        $sql = 'DELETE FROM ' . static::$table . ' WHERE id = :id';
        $params = [
            ':id' => $this->id
        ];
        $db = Db::getInstance();
        return $db->execute($sql, $params);
    }

    /**
     * Возвращает количество записей в таблице
     * @return bool|int
     */
    public static function count()
    {
        $sql = 'SELECT COUNT(*) count FROM ' . static::$table;
        $db = Db::getInstance();
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
            if ($value === '') $value = null;
            $this->$key = $value;
        }
        return $this;
    }
}
