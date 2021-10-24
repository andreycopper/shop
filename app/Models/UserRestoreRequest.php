<?php

namespace Models;

use System\Db;
use System\Logger;
use Exceptions\DbException;

class UserRestoreRequest extends Model
{
    protected static $table = 'user_restore_requests';

    /**
     * Получает запрос по коду восстановления
     * @param string $hash
     * @param false $active
     * @param bool $object
     * @return false|mixed
     * @throws DbException
     */
    public static function getByHash(string $hash, $active = false, $object = true)
    {
        $sql = "SELECT * FROM user_restore_requests WHERE hash = :hash";
        $sql .= !empty($active) ? ' AND expire > NOW()' : '';
        $params = [
            ':hash' => $hash
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Создает запрос на восстановление пароля пользователя
     * @param int $id - id пользоваетля
     * @return false|mixed|string
     * @throws DbException
     */
    public static function create(int $id)
    {
        $request = new self();
        $request->user_id = $id;
        $request->hash = hash('sha256', microtime(true) . uniqid());
        $request->expire = date("Y-m-d H:i:s", time() + 60 * 60 * 24);

        if (false === $request->save()) {
            Logger::getInstance()->error(new DbException('Ошибка записи запроса на восстановление пароля в БД пользователя с id = ' . $id));
            return false;
        }

        return $request->hash;
    }

    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_user_id($value)
    {
        return (int)$value;
    }

    public function filter_hash($text)
    {
        return strip_tags(trim($text));
    }
}
