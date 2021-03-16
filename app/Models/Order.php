<?php

namespace Models;

use System\Db;
use System\Validation;
use Exceptions\DbException;
use Exceptions\UserException;

class Order extends Model
{
    protected static $table = 'orders';
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
    public $created;       // дата создания

    /**
     * Получение заказа пользователя по id (user_id нужен, чтобы кто попало не мог смотреть чужие заказы)
     * @param int $id
     * @param int $user_id
     * @param bool $object
     * @param bool $isAjax
     * @return bool|mixed
     * @throws DbException
     * @throws UserException
     */
    public static function getByIdAndUserId(int $id, int $user_id, bool $object = true, bool $isAjax = false)
    {
        $hash = ($user_id === 2) ? 'AND up.user_hash = :user_hash' : '';
        $params = [
            ':id' => $id,
            ':user_id' => $user_id
        ];
        if ($user_id === 2) $params[':user_hash'] = $_COOKIE['user'];
        $sql = "
            SELECT o.* 
            FROM orders o 
            LEFT JOIN user_profiles up ON up.id = o.profile_id 
            WHERE o.id = :id AND up.user_id = :user_id {$hash}
        ";
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        if (!$data) self::returnError('Не найден заказ пользователя', $isAjax);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Проверка данных в заказе
     * @param $form
     * @param $cart
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkData($form, $cart, bool $isAjax = false)
    {
        if (self::checkCart($cart, $isAjax))
            if (self::checkPersonalData($form, $isAjax))
                if (self::checkDelivery($form, $isAjax))
                    if (self::checkPayment($form, $isAjax)) return true;

        return false;
    }

    /**
     * Проверка наличия корзины
     * @param $cart
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkCart($cart, bool $isAjax = false)
    {
        if (empty($cart) || !is_array($cart['items'])) self::returnError('Не найдена корзина пользователя', $isAjax);
        return true;
    }

    /**
     * Проверка персональных данных
     * @param array $form
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkPersonalData(array $form, bool $isAjax = false)
    {
        if ($form['type'] === '1') return self::checkPhysicalData($form, $isAjax);
        elseif ($form['type'] === '2') return self::checkJuridicalData($form, $isAjax);
        else return false;
    }

    /**
     * Проверка данных физического лица
     * @param array $form
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkPhysicalData(array $form, bool $isAjax = false)
    {
        if (empty(trim($form['p_name'])) || !Validation::name(trim($form['p_name']))) self::returnError('Не заполнено поле "Контактное лицо"', $isAjax);
        if (empty(trim($form['p_email'])) || !Validation::email(trim($form['p_email']))) self::returnError('Не заполнено поле "E-mail"', $isAjax);
        if (empty(trim($form['p_phone'])) || !Validation::phone(trim($form['p_phone']))) self::returnError('Не заполнено поле "Телефон"', $isAjax);
        return true;
    }

    /**
     * Проверка данных юридического лица
     * @param array $form
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkJuridicalData(array $form, bool $isAjax = false)
    {
        if (empty(trim($form['j_name'])) || !Validation::name(trim($form['j_name']))) self::returnError('Не заполнено поле "Контактное лицо"', $isAjax);
        if (empty(trim($form['j_email'])) || !Validation::email(trim($form['j_email']))) self::returnError('Не заполнено поле "E-mail"', $isAjax);
        if (empty(trim($form['j_phone'])) || !Validation::phone(trim($form['j_phone']))) self::returnError('Не заполнено поле "Телефон"', $isAjax);
        if (empty(trim($form['company'])) || !Validation::name(trim($form['company']))) self::returnError('Не заполнено поле "Название компании"', $isAjax);
        if (empty(trim($form['j_address']))) self::returnError('Не заполнено поле "Юридический адрес"', $isAjax);
        if (empty(trim($form['inn'])) || !Validation::numbers(intval(trim($form['inn'])))) self::returnError('Не заполнено поле "ИНН"', $isAjax);
        return true;
    }

    /**
     * Проверка данных доставки
     * @param array $form
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkDelivery(array $form, bool $isAjax = false)
    {
        $delivery = intval($form['delivery']);
        if (empty($delivery)) self::returnError('Не заполнено поле "Доставка"', $isAjax);
        if ($delivery !== 1 && empty(intval($form['city_id']))) self::returnError('Не заполнено поле "Населенный пункт"', $isAjax);
        if ($delivery !== 1 && empty(intval($form['street_id']))) self::returnError('Не заполнено поле "Улица"', $isAjax);
        if ($delivery !== 1 && empty($form['house'])) self::returnError('Не заполнено поле "Дом"', $isAjax);
        return true;
    }

    /**
     * Проверка данных оплаты
     * @param array $form
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkPayment(array $form, bool $isAjax = false)
    {
        if (empty(intval($form['payment'])) || !Validation::numbers(intval(trim($form['payment'])))) self::returnError('Не заполнено поле "Оплата"', $isAjax);
        return true;
    }

    /**
     * Сохранение заказа
     * @param array $form
     * @param array $cart
     * @param int $profile_id
     * @param bool $isAjax
     * @return int
     * @throws UserException
     * @throws DbException
     */
    public function saveOrder(array $form, array $cart, int $profile_id, bool $isAjax = false):int
    {
        $order = new self();
        $order->status_id = 1;
        $order->profile_id = $profile_id;
        $order->payment_id = intval($form['payment']);
        $order->delivery_id = intval($form['delivery']);
        $order->count = $cart['count_items'];
        $order->sum = $cart['discount_sum'] ?: $cart['sum'];
        $order->created = date('Y-m-d');
        $order_id = $order->save();

        if (!$order_id) self::returnError('Не удалось сохранить заказ пользователя', $isAjax);
        return $order_id;
    }

    /**
     * Обновление корзины - просвоение номера заказа товарам
     * @param int $user_id
     * @param int $order_id
     * @param bool $isAjax
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function moveCartToOrder(int $user_id, int $order_id, bool $isAjax = false)
    {
        $user_hash = ($user_id === 2) ? 'AND oi.user_hash = :user_hash' : '';
        $params = [
            ':user_id' => $user_id,
            ':order_id' => $order_id
        ];
        if ($user_id === 2) $params[':user_hash'] = $_COOKIE['user'];
        $sql = "
            UPDATE order_items oi
            SET oi.order_id = :order_id
            WHERE oi.user_id = :user_id {$user_hash} AND oi.order_id IS NULL
        ";

        $db = new Db();
        $res = $db->iquery($sql, $params);
        if (!$res) self::returnError('Не удалось присвоить номер заказа товарам из корзины', $isAjax);
        return $order_id;
    }
}
