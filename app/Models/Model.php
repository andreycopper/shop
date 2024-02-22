<?php

namespace Models;

use Entity\Entity;
use Models\Product\ProductImage;
use Models\Product\ProductPrice;
use Models\Product\ProductStore;
use ReflectionClass;
use ReflectionException;
use System\Db;
use Traits\Magic;
use Traits\CastableToArray;

/**
 * Class Model
 * @package App\Models
 */
abstract class Model
{
    protected static $db_prefix = CONFIG['db']['dbprefix'];
    protected static $db_table = null;

    use Magic;
    use CastableToArray;

//    /**
//     * Создает объект вызвавшего класса и заполняет его свойства
//     * @param object|array $data
//     * @return array|object|false
//     */
//    public static function factory(object|array $data)
//    {
//        if (!empty($data)) {
//            if (is_array($data)) {
//                $res = [];
//                foreach ($data as $item) {
//                    $res[] = self::init($item);
//                }
//                return $res;
//            } elseif (is_object($data)) {
//                return self::init($data);
//            }
//        }
//
//        return false;
//    }

    /**
     * Инициализиурет model из entity
     * @param Entity|null $data - объект entity
     * @return $this|null
     * @throws ReflectionException
     */
    public function init(?Entity $data)
    {
        if (empty($data)) return null;

        $fields = $data->getFields();
        $class = new ReflectionClass($data);

        foreach ($fields as $key => $field) {
            if (!property_exists($this, $key)) continue;

            $prop = $field['field'];

            $property = $class->getProperty($prop);
            $property->setAccessible(true);

            switch ($field['type']) {
                case 'int':
                    $this->$key = (int) $property->getValue($data);
                    //$this->$key = (int) $data->$prop;
                    break;
                case 'float':
                    $this->$key = (float) $property->getValue($data);
                    //$this->$key = (float) $data->$prop;
                    break;
                case 'string':
                    $this->$key = (string) $property->getValue($data);
                    //$this->$key = (string) $data->$prop;
                    break;
                case 'bool':
                    $value = $property->getValue($data);
                    $this->$key = !empty($value) ? (bool) $value : null;
                    //$this->$key = !empty($data->$prop) ? (bool) $data->$prop : null;
                    break;
                case 'datetime':
                    $value = $property->getValue($data);
                    $this->$key =
                        //!empty($data->$prop) ?
                        !empty($value) ?
                            //($data->$prop instanceof \DateTime ?
                            ($value instanceof \DateTime ?
                                //$data->$prop->format('Y-m-d H:i:s') :
                                $value->format('Y-m-d H:i:s') :
                                //(is_string($data->$prop) ? $data->$prop : null)) :
                                (is_string($value) ? $value : null)) :
                            null;
                    break;
                default:
                    $this->$key = $data->$prop;
            }
        }

        return $this;
    }

    public function toArray()
    {
        $result = [];
        foreach ($this as $key => $value) {
            if ($key === 'data') continue;
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Находит и возвращает записи из БД
     * @param $params
     * $params['active'] - только активные сообщения
     * $params['sort'] - поле сортировки
     * $params['order'] - направление сортировки
     * $params['limit'] - лимит сообщений для выдачи
     * @return array|bool
     */
    public static function getList(array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = static::$db_prefix;
        $table = static::$db_table;

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'WHERE active IS NOT NULL' : '';
        $sort = !empty($params['sort']) ? $params['sort'] : 'id';
        $order = !empty($params['order']) ? strtoupper($params['order']) : 'ASC';
        $limit = !empty($params['limit']) ? "LIMIT {$params['limit']}" : '';

        $db->params = [];
        $db->sql = "
            SELECT * 
            FROM {$prefix}{$table} 
            {$active} 
            ORDER BY {$sort} {$order} 
            {$limit}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return $data ?? false;
    }

    /**
     * Находит и возвращает одну запись из БД по id
     * @param int $id
     * @param array|null $params
     * @return bool|mixed
     */
    public static function getById(int $id, array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = static::$db_prefix;
        $table = static::$db_table;

        $db = Db::getInstance();
        $active = !empty($params['active']) ? ' AND active IS NOT NULL' : '';
        $db->params = ['id' => $id];
        $db->sql = "SELECT * FROM {$prefix}{$table} WHERE id = :id {$active}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Возвращает имя страницы по любому полю (+)
     * @param string $field
     * @param string $value
     * @param array|null $params
     * @return false
     */
    public static function getName(string $field, string $value, array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = static::$db_prefix;
        $table = static::$db_table;

        $db = Db::getInstance();
        $active = !empty($params['active']) ? ' AND active IS NOT NULL' : '';
        $db->params = ['value' => $value];
        $db->sql = "SELECT name FROM shop." . static::$db_table . " WHERE {$field} = :value {$active}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
        return !empty($data) ? array_shift($data)['name'] : false;
    }

    /**
     * Находит и возвращает одну запись из БД по полю и его значению
     * @param string $field
     * @param string $value
     * @return array|false
     */
    public static function getByField(string $field, string $value, array $params = [])
    {
        $params += ['active' => true, 'object' => false];
        $prefix = static::$db_prefix;
        $table = static::$db_table;

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND active IS NOT NULL' : '';
        $db->params = ['value' => $value];

        $db->sql = "SELECT * FROM shop." . static::$db_table . " WHERE {$field} = :value {$active}";

        $data = $db->query(!empty($params['object']) ? static::class : null);
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
        return !(!empty($this->id) && !empty(self::getById($this->id, ['active' => false])));
    }

    /**
     * Добавляет запись в БД
     * @return bool|int
     */
    public function insert()
    {
        $db = Db::getInstance();
        $cols = [];
        $db->params = [];
        foreach ($this as $key => $val) {
            if ($val === null) continue;
            if ($key === 'data') continue;
            $cols[] = $key;
            $db->params[$key] = $val;
        }
        $db->sql =  "
            INSERT INTO " . self::$db_prefix . static::$db_table . " (" . implode(', ', $cols) . ") 
            VALUES (" . ":" . implode(', :', $cols) . ")";

        $res = $db->execute();
        return !empty($res) ? $db->lastInsertId() : false;
    }

    /**
     * Обновляет запись в БД
     */
    public function update()
    {
        $db = Db::getInstance();
        $binds = [];
        $db->params = [];
        foreach ($this as $key => $val) {
            //if ($val === null) continue;
            if ($key === 'data') continue;
            if ('id' !== $key) $binds[] = $key . ' = :' . $key;
            $db->params[$key] = $val;
        }
        $db->sql = 'UPDATE ' . self::$db_prefix . static::$db_table . ' SET ' . implode(', ', $binds) . ' WHERE id = :id';

        return $db->execute() ? $this->id : false;
    }

    /**
     * Удаляет запись из БД
     * @return bool
     */
    public function delete(): bool
    {
        $db = Db::getInstance();
        $db->params = [':id' => $this->id];
        $db->sql = "DELETE FROM " . self::$db_prefix . static::$db_table . " WHERE id = :id";
        return $db->execute();
    }

    /**
     * Возвращает количество записей в таблице
     * @return bool|int
     */
    public static function count()
    {
        $db = Db::getInstance();
        $db->sql = "SELECT COUNT(*) count FROM " . self::$db_prefix . static::$db_table;
        $data = $db->query(static::class);
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
