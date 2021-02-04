<?php

namespace App\Models;

use App\Exceptions\EditException;
use App\Exceptions\UserException;
use App\System\Db;
use App\System\Logger;
use App\System\Request;

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
     * Получение заказа пользователя по id
     * @param int $id
     * @param int $user_id
     * @param null $user_hash
     * @param bool $object
     * @return bool|mixed
     * @throws \App\Exceptions\DbException
     */
    public static function getByIdAndUserId(int $id, int $user_id, $user_hash = null, bool $object = true)
    {
        $where = !empty($user_hash) ? 'AND up.user_hash = :user_hash' : '';
        $sql = "
            SELECT o.* 
            FROM orders o 
            LEFT JOIN user_profiles up ON up.id = o.profile_id 
            WHERE o.id = :id AND up.user_id = :user_id {$where}
        ";
        $params = [
            ':id' => $id,
            ':user_id' => $user_id
        ];
        if (!empty($user_hash)) $params[':user_hash'] = $user_hash;
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
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
    public static function checkData($form, $cart, bool $isAjax) {
        if ($form['type'] === '1') return self::checkPhysicalData($form, $cart, $isAjax);
        elseif ($form['type'] === '2') return self::checkJuridicalData($form, $cart, $isAjax);
        else return false;
    }

    /**
     * Проверка данных физического лица
     * @param $form
     * @param $cart
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkPhysicalData($form, $cart, bool $isAjax) {
        $name = trim($form['p_name']);
        $email = trim($form['p_email']);
        $phone = trim($form['p_phone']);
        $delivery = intval($form['delivery']);
        $payment = intval($form['payment']);
        $city_id = intval($form['city_id']);
        $address = trim($form['address']);

        if ($cart) {
            if (!empty($name)) {
                if (!empty($email)) {
                    if (!empty($phone)) {
                        if (!empty($delivery)) {
                            if (!empty($payment)) {
                                if ($delivery !== 1) {
                                    if (empty($city_id)) $message = 'Не заполнено поле "Адрес доставки"';
                                    if (empty($address)) $message = 'Не заполнено поле "Населенный пункт"';
                                }
                            } else $message = 'Не заполнено поле "Оплата"';
                        } else $message = 'Не заполнено поле "Доставка"';
                    } else $message = 'Не заполнено поле "Телефон"';
                } else $message = 'Не заполнено поле "E-mail"';
            } else $message = 'Не заполнено поле "Контактные данные"';
        } else $message = 'Не найдена корзина';

        if (!empty($message)) self::returnError($message, $isAjax);
        return true;
    }

    /**
     * Проверка данных юридического лица
     * @param $form
     * @param $cart
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     */
    public static function checkJuridicalData($form, $cart, bool $isAjax) {
        $name = trim($form['j_name']);
        $email = trim($form['j_email']);
        $phone = trim($form['j_phone']);
        $company = trim($form['company']);
        $j_address = trim($form['j_address']);
        $inn = trim($form['inn']);
        $kpp = trim($form['kpp']);
        $delivery = intval($form['delivery']);
        $payment = intval($form['payment']);
        $city_id = intval($form['city_id']);
        $address = trim($form['address']);

        if ($cart) {
            if (!empty($name)) {
                if (!empty($email)) {
                    if (!empty($phone)) {
                        if (!empty($company)) {
                            if (!empty($j_address)) {
                                if (!empty($inn)) {
                                    if (!empty($kpp)) {
                                        if (!empty($delivery)) {
                                            if (!empty($payment)) {
                                                if ($delivery !== 1) {
                                                    if (empty($city_id)) $message = 'Не заполнено поле "Адрес доставки"';
                                                    if (empty($address)) $message = 'Не заполнено поле "Населенный пункт"';
                                                }
                                            } else $message = 'Не заполнено поле "Оплата"';
                                        } else $message = 'Не заполнено поле "Доставка"';
                                    } else $message = 'Не заполнено поле "КПП"';
                                } else $message = 'Не заполнено поле "ИНН"';
                            } else $message = 'Не заполнено поле "Юридический адрес"';
                        } else $message = 'Не заполнено поле "Название компании"';
                    } else $message = 'Не заполнено поле "Телефон"';
                } else $message = 'Не заполнено поле "E-mail"';
            } else $message = 'Не заполнено поле "Контактные данные"';
        } else $message = 'Не найдена корзина';

        if (!empty($message)) self::returnError($message, $isAjax);
        return true;
    }

    /**
     * Сохранение заказа
     * @param $form
     * @param $cart
     * @param int $profile_id
     * @param bool $isAjax
     * @return int
     * @throws UserException
     * @throws \App\Exceptions\DbException
     */
    public function saveOrder($form, $cart, int $profile_id, bool $isAjax):int
    {
        $order = new Order();
        $order->status_id = 1;
        $order->profile_id = $profile_id;
        $order->payment_id = intval($form['payment']);
        $order->delivery_id = intval($form['delivery']);
        $order->count = $cart['count_items'];
        $order->sum = $cart['sum'];
        $order->created = date('Y-m-d');

        $order_id = $order->save();
        if (!$order_id) self::returnError('Не удалось сохранить заказ пользователя', $isAjax);

        return $order_id;
    }

    /**
     * Обновление корзины - просвоение номера заказа
     * @param array $items
     * @param int $order_id
     * @param bool $isAjax
     * @return bool
     * @throws UserException
     * @throws \App\Exceptions\DbException
     */
    public static function updateCart(array $items, int $order_id, bool $isAjax)
    {
        if (empty($items) || !is_array($items)) self::returnError('Не обнаружены товары в корзине', $isAjax);

        foreach ($items as $item) {
            $item->order_id = $order_id;
            if (!OrderItem::factory($item)->save()) self::returnError('Не удалось обновить в корзине товар id=' . $item->id, $isAjax);
        }
        return true;
    }
}
