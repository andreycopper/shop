<?php

namespace Models;

use System\Db;
use System\Validation;
use Exceptions\DbException;

class Order extends Model
{
    protected static $db_table = 'orders';
    public $id;           // id
    public $status_id;    // статус
    public $profile_id;   // профиль
    public $payment_id;   // способ оплаты
    public $payment_date; // дата полной оплаты
    public $is_paid;      // оплачено
    public $delivery_id;   // способ доставки
    public $delivery_date; // дата доставки
    public $is_delivered;  // доставлено
    public $count;         // количество товаров
    public $sum;           // сумма
    public $sum_nds;       // сумма НДС
    public $economy;       // скидка
    public $created;       // дата создания

    /**
     * Получение заказа пользователя по id
     * @param int $id - id заказа
     * @param int $user_id - id пользователя (нужен, чтобы кто попало не мог смотреть чужие заказы)
     * @param $user_hash - хэш пользователя
     * @param bool $object - возвращать объект/массив
     * @return bool|mixed
     * @throws DbException
     */
    public static function getByIdUserId(int $id, int $user_id, $user_hash, bool $object = true)
    {
        $userHash = ($user_id === 2) ? 'AND up.user_hash = :user_hash' : '';
        $params = [
            ':id' => $id,
            ':user_id' => $user_id
        ];
        if ($user_id === 2) $params[':user_hash'] = $user_hash;
        $sql = "
            SELECT o.* 
            FROM orders o 
            LEFT JOIN user_profiles up ON up.id = o.profile_id 
            WHERE o.id = :id AND up.user_id = :user_id {$userHash}
        ";
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Проверка данных в заказе
     * @param array $form - форма с данными
     * @param array $cart - корзина пользователя
     * @return bool
     */
    public static function checkData(array $form, array $cart)
    {
        if (self::checkCart($cart))
            if (self::checkPersonalData($form))
                if (self::checkDelivery($form))
                    if (self::checkPayment($form)) return true;

        return false;
    }

    /**
     * Проверка наличия корзины
     * @param array $cart - корзина пользователя
     * @return bool
     */
    public static function checkCart(array $cart)
    {
        if (empty($cart) || !is_array($cart['items'])) return false;
        return true;
    }

    /**
     * Проверка персональных данных
     * @param array $form - форма с данными
     * @return bool
     */
    public static function checkPersonalData(array $form)
    {
        if ($form['type'] === '1') return self::checkPhysicalData($form);
        elseif ($form['type'] === '2') return self::checkJuridicalData($form);
        else return false;
    }

    /**
     * Проверка данных физического лица
     * @param array $form - форма с данными
     * @return bool
     */
    public static function checkPhysicalData(array $form)
    {
        if (empty(trim($form['p_name'])) || !Validation::name(trim($form['p_name']))) return false;
        if (empty(trim($form['p_email'])) || !Validation::email(trim($form['p_email']))) return false;
        if (empty(trim($form['p_phone'])) || !Validation::phone(trim($form['p_phone']))) return false;
        return true;
    }

    /**
     * Проверка данных юридического лица
     * @param array $form - форма с данными
     * @return bool
     */
    public static function checkJuridicalData(array $form)
    {
        if (empty(trim($form['j_name'])) || !Validation::name(trim($form['j_name']))) return false;
        if (empty(trim($form['j_email'])) || !Validation::email(trim($form['j_email']))) return false;
        if (empty(trim($form['j_phone'])) || !Validation::phone(trim($form['j_phone']))) return false;
        if (empty(trim($form['company'])) || !Validation::name(trim($form['company']))) return false;
        if (empty(trim($form['j_address']))) return false;
        if (empty(trim($form['inn'])) || !Validation::numbers(intval(trim($form['inn'])))) return false;
        return true;
    }

    /**
     * Проверка данных доставки
     * @param array $form - форма с данными
     * @return bool
     */
    public static function checkDelivery(array $form)
    {
        $delivery = intval($form['delivery']);
        if (empty($delivery)) return false;
        if ($delivery !== 1 && empty(intval($form['city_id']))) return false;
        if ($delivery !== 1 && empty(intval($form['street_id']))) return false;
        if ($delivery !== 1 && empty($form['house'])) return false;
        return true;
    }

    /**
     * Проверка данных оплаты
     * @param array $form - форма с данными
     * @return bool
     */
    public static function checkPayment(array $form)
    {
        if (empty(intval($form['payment'])) || !Validation::numbers(intval(trim($form['payment'])))) return false;
        return true;
    }

    /**
     * Сохранение заказа
     * @param array $form - форма с данными
     * @param array $cart - корзина пользователя
     * @param int $profile_id - id профиля пользователя
     * @return int
     * @throws DbException
     */
    public function saveOrder(array $form, array $cart, int $profile_id)
    {
        $order = new self();
        $order->status_id = 1;
        $order->profile_id = $profile_id;
        $order->payment_id = intval($form['payment']);
        $order->delivery_id = intval($form['delivery']);
        $order->count = $cart['count_items'];
        $order->sum = $cart['economy'] > 0 ? $cart['sum_discount'] : $cart['sum'];
        $order->sum_nds = $cart['economy'] > 0 ? $cart['sum_discount_nds'] : $cart['sum_nds'];
        $order->economy = $cart['economy'];
        $order->created = date('Y-m-d');
        $order_id = $order->save();

        return $order_id ?? false;
    }

    /**
     * Обновление корзины - просвоение номера заказа товарам
     * @param int $user_id - id пользователя
     * @param int $order_id - id заказа
     * @return bool
     * @throws DbException
     */
    public static function moveCartToOrder(int $order_id, int $user_id, string $user_hash)
    {
        $userHash = ($user_id === 2) ? 'AND oi.user_hash = :user_hash' : '';
        $params = [
            ':user_id' => $user_id,
            ':order_id' => $order_id
        ];
        if ($user_id === 2) $params[':user_hash'] = $user_hash;
        $sql = "
            UPDATE order_items oi
            SET oi.order_id = :order_id
            WHERE oi.user_id = :user_id {$userHash} AND oi.order_id IS NULL AND oi.qorder_id IS NULL
        ";

        $db = Db::getInstance();
        return $db->execute($sql, $params);
    }
}
