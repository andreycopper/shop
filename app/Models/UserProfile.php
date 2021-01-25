<?php

namespace App\Models;

use App\Exceptions\DbException;
use App\System\Db;

class UserProfile extends Model
{
    protected static $table = 'user_profiles';
    public $id;            // id
    public $active;        // активность
    public $user_id;       // id пользователя
    public $user_hash;     // хэш пользователя
    public $user_type_id;  // id типа пользователя (физическое/юридическое лицо)
    public $city_id;       // id города
    public $address;       // адрес доставки
    public $phone;         // телефон
    public $email;         // email
    public $name;          // контактное лицо
    public $comment;       // комментарий
    public $company;       // название организации
    public $address_legal; // юридический адрес
    public $ogrn;          // ОГРН
    public $inn;           // ИНН
    public $kpp;           // КПП
    public $created;       // дата создания
    public $updated;       // дата обновления

    /**
     * Находит и возвращает записи из БД
     * @param int $user_id
     * @param bool $active
     * @param bool $object
     * @return array|bool
     * @throws DbException
     */
    public static function getListByUserId(int $user_id, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND up.active IS NOT NULL AND u.active IS NOT NULL AND u.blocked IS NULL' : '';
        $sql = "
            SELECT up.*, 
                   u.last_name AS user_last_name, u.name AS user_name, u.second_name AS user_second_name, 
                   u.email AS user_email, u.phone AS user_phone,
                   c.name AS city 
            FROM user_profiles up 
            LEFT JOIN users u 
                ON up.user_id = u.id 
            LEFT JOIN fias_cities c 
                ON c.id = up.city_id
            WHERE up.user_id = :user_id {$where}
            ";
        $params = [
            ':user_id' => $user_id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    public static function getListByUserHash(string $user_hash, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND up.active IS NOT NULL AND u.active IS NOT NULL AND u.blocked IS NULL' : '';
        $sql = "
            SELECT up.*, 
                   u.last_name AS user_last_name, u.name AS user_name, u.second_name AS user_second_name, 
                   u.email AS user_email, u.phone AS user_phone,
                   c.name AS city 
            FROM user_profiles up 
            LEFT JOIN users u 
                ON up.user_id = u.id 
            LEFT JOIN fias_cities c 
                ON c.id = up.city_id
            WHERE up.user_hash = :user_hash {$where}
            ";
        $params = [
            ':user_hash' => $user_hash
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    public static function getListByUser(bool $active = false)
    {
        $user = User::getCurrent();
        return !empty($user['id']) ?
            self::getListByUserId($user['id'], $active) :
            self::getListByUserHash($_COOKIE['user'], $active);

    }
}
