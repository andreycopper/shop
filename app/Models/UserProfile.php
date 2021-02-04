<?php

namespace App\Models;

use App\Exceptions\DbException;
use App\Exceptions\UserException;
use App\System\Db;
use App\System\Logger;
use App\System\Request;
use App\System\RSA;

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

    public static function getByIdAndUserId(int $id, int $user_id, bool $active = true, bool $object = true)
    {
        $where = !empty($active) ? ' AND active IS NOT NULL' : '';
        $sql = "SELECT * FROM " . static::$table . " WHERE id = :id AND user_id = :user_id {$where}";
        $params = [
            ':id' => $id,
            ':user_id' => $user_id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

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

    public function saveProfile($form, $user_id, $user_type, $isAjax)
    {
        $user = User::getById($user_id);
        if ($user_type === 1) return $this->savePhisicalProfile($form, $user, $isAjax);
        elseif ($user_type === 2) return $this->saveJuridicalProfile($form, $user, $isAjax);
        else return false;
    }

    protected function savePhisicalProfile($form, $user, $isAjax)
    {
        $name = trim($form['p_name']);
        $email = trim($form['p_email']);
        $phone = trim($form['p_phone']);
        $delivery = intval($form['delivery']);
        $city_id = intval($form['city_id']);
        $address = trim($form['address']);
        $comment = trim($form['comment']);

        $user_profile =
            (Request::post('p_profile') === '0') ?
                (new UserProfile()) :
                self::getByIdAndUserId(Request::post('p_profile'), $user->id);

        $user_profile->active = 1;
        $user_profile->user_id = $user->id;
        $user_profile->user_hash = $_COOKIE['user'];
        $user_profile->user_type_id = 1;
        $user_profile->phone = $phone;
        $user_profile->email = $email;
        $user_profile->name = (new RSA($user->private_key))->encrypt($name);
        $user_profile->created = $user_profile->created ?? date('Y-m-d');
        $user_profile->updated = $user_profile->id ? date('Y-m-d') : null;

        if ($delivery !== 1) {
            $user_profile->city_id = $city_id;
            $user_profile->address = $address;
            $user_profile->comment = $comment;
        }

        $profile_id = $user_profile->save();

        if (!$profile_id) self::returnError('Не удалось сохранить профиль пользователя', $isAjax);
        return $profile_id;
    }

    protected function saveJuridicalProfile($form, $user, $isAjax)
    {
        $name = trim($form['j_name']);
        $email = trim($form['j_email']);
        $phone = trim($form['j_phone']);
        $company = trim($form['company']);
        $j_address = trim($form['j_address']);
        $inn = trim($form['inn']);
        $kpp = trim($form['kpp']);
        $delivery = intval($form['delivery']);
        $city_id = intval($form['city_id']);
        $address = trim($form['address']);
        $comment = trim($form['comment']);

        $user_profile =
            (Request::post('j_profile') === '0') ?
                (new UserProfile()) :
                self::getByIdAndUserId(Request::post('j_profile'), $user->id);

        $user_profile->active = 1;
        $user_profile->user_id = $user->id;
        $user_profile->user_hash = $_COOKIE['user'];
        $user_profile->user_type_id = 2;
        $user_profile->phone = $phone;
        $user_profile->email = $email;
        $user_profile->name = (new RSA($user->private_key))->encrypt($name);
        $user_profile->company = $company;
        $user_profile->address_legal = $j_address;
        $user_profile->inn = $inn;
        $user_profile->kpp = $kpp;
        $user_profile->created = $user_profile->created ?? date('Y-m-d');
        $user_profile->updated = $user_profile->id ? date('Y-m-d') : null;

        if ($delivery !== 1) {
            $user_profile->city_id = $city_id;
            $user_profile->address = $address;
            $user_profile->comment = $comment;
        }

        $profile_id = $user_profile->save();

        if (!$profile_id) self::returnError('Не удалось сохранить профиль пользователя', $isAjax);
        return $profile_id;
    }
}
