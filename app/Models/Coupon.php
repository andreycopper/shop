<?php

namespace App\Models;

use App\System\Db;
use App\System\Logger;
use App\Exceptions\UserException;

class Coupon extends Model
{
    protected static $table = 'coupons';
    public $id;             // id записи
    public $active;         // активность
    public $active_from;    // активность от даты
    public $active_to;      // активность до даты
    public $name;           // название купона
    public $code;           // код купона
    public $coupon_term_id; // id условий применения
    public $value;          // значение условия выборки (*/product_id/group_id/name - все товары/id товара/id категории/название)
    public $discount;       // скидка
    public $created;        // дата создания

    public static function getByCodeUserId(string $code, int $user_id, bool $active = false, bool $object = true)
    {
        $where = !empty($active) ? 'AND c.active IS NOT NULL AND ct.active IS NOT NULL' : '';
        $sql = "
            SELECT c.*, ct.term, cu.created AS used  
            FROM coupons c 
            LEFT JOIN coupon_terms ct 
                ON ct.id = c.coupon_term_id 
            LEFT JOIN coupon_usages cu 
                ON cu.coupon_id = c.id AND cu.user_id = :user_id
            WHERE c.code = :code {$where}
            ";
        $params = [
            ':code' => $code,
            ':user_id' => $user_id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    public static function getByCodeUserHash(string $code, string $user_hash, bool $active = false, bool $object = true)
    {
        $where = !empty($active) ? 'AND c.active IS NOT NULL AND ct.active IS NOT NULL' : '';
        $sql = "
            SELECT c.*, ct.term, cu.created AS used  
            FROM coupons c 
            LEFT JOIN coupon_terms ct 
                ON ct.id = c.coupon_term_id 
            LEFT JOIN coupon_usages cu 
                ON cu.coupon_id = c.id AND cu.user_hash = :user_hash 
            WHERE c.code = :code {$where}
            ";
        $params = [
            ':code' => $code,
            ':user_hash' => $user_hash
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    public static function get(string $coupon)
    {
        if (!empty($coupon)) {
            $user = User::getCurrent();
            $item = !empty($user['id']) ?
                self::getByCodeUserId($coupon, $user['id']) :
                self::getByCodeUserHash($coupon, $_COOKIE['user']);
        }

        return $item ?? false;
    }

    /**
     * Проверяет введенный купон на скидку
     * @param Coupon $coupon
     * @param false $isAjax
     * @return bool
     */
    public static function check(Coupon $coupon, $isAjax = false)
    {
        if (!empty($coupon)) { // купон найден
                if ($coupon->active) { // купон активен
                    if (!$coupon->used) { // купон не использован
                        if ($isAjax) {
                            echo json_encode([
                                'result' => true
                            ]);
                            die;
                        } else return true;
                    } else $message = 'Данный купон уже использован';
                } else $message = 'Введен неактивный купон';
        } else $message = 'Не введен купон';

        Logger::getInstance()->error(new UserException($message));

        if ($isAjax) {
            echo json_encode([
                'result' => false,
                'message' => $message
            ]);
            die;
        } else return false;
    }

    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_active($value)
    {
        return (int)$value;
    }

    public function filter_name($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_code($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_coupon_term_id($value)
    {
        return (int)$value;
    }
}