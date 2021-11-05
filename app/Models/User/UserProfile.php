<?php

namespace Models\User;

use System\Db;
use System\RSA;
use Models\Model;
use System\Request;
use Exceptions\DbException;

class UserProfile extends Model
{
    protected static $table = 'user_profiles';
    public $id;              // id
    public $active;              // активность
    public $user_id;         // id пользователя
    public $user_hash;    // хэш пользователя
    public $user_type_id;    // id типа пользователя (физическое/юридическое лицо)
    public $city_id;             // id города
    public $village_id;          // id деревни
    public $street_id;           // id улицы
    public $block_id;            // id блока
    public $structure_id;        // id планировочной структуры
    public $territory_id;        // id территории
    public $territory_street_id; // id улицы на территории
    public $house;        // дом
    public $building;            // корпус
    public $flat;                // квартира
    public $phone;        // телефон
    public $email;        // email
    public $name;         // контактное лицо
    public $comment;             // комментарий
    public $company;             // название организации
    public $address_legal;       // юридический адрес
    public $inn;                 // ИНН
    public $kpp;                 // КПП
    public $created;      // дата создания
    public $updated;             // дата обновления

    /**
     * Получает список профилей пользователя
     * @param int $user_id
     * @param string $user_hash
     * @param bool $active
     * @param bool $object
     * @return array|false
     */
    public static function getListByUser(int $user_id, string $user_hash, bool $active = true, $object = true)
    {
        $activity = !empty($active) ? 'AND up.active IS NOT NULL AND u.active IS NOT NULL AND u.blocked IS NULL' : '';
        $params = [':user_id' => $user_id];
        $userHash = $user_id === 2 ? 'AND up.user_hash = :user_hash' : '';
        if ($user_id === 2) $params[':user_hash'] = $user_hash;
        $sql = "
            SELECT up.id, up.user_id, up.user_hash, up.user_type_id, 
                   up.city_id, c.name city, sn1.shortname city_shortname, 
                   up.street_id, s.name street, sn2.shortname street_shortname, 
                   up.house, up.building, up.flat, 
                   up.phone, up.email, up.name, up.comment, 
                   up.company, up.address_legal, up.inn, up.kpp, 
                   up.created, up.updated,                   
                   u.last_name AS user_last_name, u.name AS user_name, u.second_name AS user_second_name, 
                   u.email AS user_email, u.phone AS user_phone 
            FROM user_profiles up 
            LEFT JOIN users u 
                ON up.user_id = u.id 
            LEFT JOIN fias.cities c 
                ON c.id = up.city_id 
            LEFT JOIN fias.streets s 
                ON s.id = up.street_id 
            LEFT JOIN fias.shortnames sn1 
                ON sn1.id = c.shortname_id 
            LEFT JOIN fias.shortnames sn2 
                ON sn2.id = s.shortname_id 
            WHERE up.user_id = :user_id {$userHash} {$activity}";
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Сохранение профиля пользователя
     * @param array $form - форма с данными
     * @param User $user - пользователь
     * @return bool|int
     * @throws DbException
     */
    public function saveProfile(array $form, User $user)
    {
        if ($form['type'] === '1') return $this->savePhisicalProfile($form, $user);
        elseif ($form['type'] === '2') return $this->saveJuridicalProfile($form, $user);
        return false;
    }

    /**
     * Сохранение профиля физического лица
     * @param array $form - форма с данными
     * @param User $user - пользователь
     * @return bool|int
     */
    protected function savePhisicalProfile(array $form, User $user)
    {
        $user_profile =
            ($form['p_profile'] === '0') ? (new UserProfile()) : self::getByIdUserId($form['p_profile'], $user->id);

        $user_profile->active       = 1;
        $user_profile->user_id      = $user->id;
        $user_profile->user_hash    = $_COOKIE['user'];
        $user_profile->user_type_id = 1;
        $user_profile->phone        = trim($form['p_phone']);
        $user_profile->email        = trim($form['p_email']);
        $user_profile->name         = (new RSA($user->private_key))->encrypt(trim($form['p_name']));
        $user_profile->updated      = $user_profile->id ? date('Y-m-d H:i:s') : null;

        if (intval($form['delivery']) !== 1) {
            $user_profile->city_id   = intval($form['city_id']);
            $user_profile->street_id = intval($form['street_id']);
            $user_profile->house     = trim($form['house']);
            $user_profile->building  = trim($form['building']) ?: null;
            $user_profile->flat      = trim($form['flat']) ?: null;
            $user_profile->comment   = trim($form['comment']) ?: null;
        }

        $profile_id = $user_profile->save();
        return $profile_id ?? false;
    }

    /**
     * Сохранение профиля юридического лица
     * @param $form - форма с данными
     * @param $user - пользователь
     * @return bool|int
     */
    protected function saveJuridicalProfile($form, $user)
    {
        $user_profile =
            ($form['j_profile'] === '0') ? (new UserProfile()) : self::getByIdUserId($form['j_profile'], $user->id);

        $user_profile->active = 1;
        $user_profile->user_id = $user->id;
        $user_profile->user_hash = $_COOKIE['user'];
        $user_profile->user_type_id = 2;
        $user_profile->phone = trim($form['j_phone']);
        $user_profile->email = trim($form['j_email']);
        $user_profile->name = (new RSA($user->private_key))->encrypt(trim($form['j_name']));
        $user_profile->company = trim($form['company']);
        $user_profile->address_legal = trim($form['j_address']);
        $user_profile->inn = trim($form['inn']);
        $user_profile->kpp = trim($form['kpp']);
        $user_profile->updated = $user_profile->id ? date('Y-m-d H:i:s') : null;

        if (intval($form['delivery']) !== 1) {
            $user_profile->city_id   = intval($form['city_id']);
            $user_profile->street_id = intval($form['street_id']);
            $user_profile->house     = trim($form['house']);
            $user_profile->building  = trim($form['building']) ?: null;
            $user_profile->flat      = trim($form['flat']) ?: null;
            $user_profile->comment   = trim($form['comment']) ?: null;
        }

        $profile_id = $user_profile->save();
        return $profile_id ?? false;
    }

    /**
     * Возвращает профиль пользователя по его id
     * @param int $id - id профиля
     * @param int $user_id - id пользователя (используется для проверки привязки профиля данному пользователя)
     * @param bool $active - активность
     * @param bool $object - возвращать объект/массив
     * @return false|mixed
     */
    public static function getByIdUserId(int $id, int $user_id, bool $active = true, bool $object = true)
    {
        $where = !empty($active) ? ' AND active IS NOT NULL' : '';
        $sql = "SELECT * FROM " . static::$table . " WHERE id = :id AND user_id = :user_id {$where}";
        $params = [
            ':id' => $id,
            ':user_id' => $user_id
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }
}
