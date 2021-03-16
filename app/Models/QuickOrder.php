<?php

namespace Models;

use System\Db;
use System\Validation;
use Exceptions\DbException;
use Exceptions\UserException;

class QuickOrder extends Model
{
    protected static $table = 'quick_orders';
    public $id;        // id
    public $user_id;   // id пользователя
    public $user_hash; // хэш пользователя
    public $name;      // имя
    public $phone;     // телефон
    public $created;   // дата создания
    public $updated;   // дата изменения

    /**
     * Проверка персональных данных
     * @param array $form
     * @return bool
     */
    public static function checkData(array $form)
    {
        if (!empty(trim($form['agreement'])))
            if (!empty(trim($form['name'])) && Validation::name(trim($form['name'])))
                if (!empty(trim($form['phone'])) && Validation::phone(trim($form['phone'])))
                    return true;

        return false;
    }

    /**
     * Проверка наличия товаров в быстром заказе
     * @param array $form
     * @param null $cart
     * @return bool
     */
    public static function checkProducts(array $form, $cart = null)
    {
        if ((!empty($form['id']) && !empty($form['count'])) || !empty($cart)) return true;
        return false;
    }

    /**
     * Сохранение быстрого заказа
     * @param $user - пользователь
     * @param $form - форма с данными (имя и телефон)
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function saveOrder($user, $form)
    {
        $qorder = new self();
        $qorder->user_id = $user->id;
        $qorder->user_hash = $_COOKIE['user'];
        $qorder->name = $form['name'];
        $qorder->phone = preg_replace('/[^0-9]/', '', $form['phone']);
        $qorder->created = date('Y-m-d H:i:s');
        $q_id = $qorder->save();

        if ($q_id) {
           if (!empty($form['id']) && !empty($form['count'])) {
               $item_id = OrderItem::add($user, $form['id'], $form['count']);
               if ($item_id) return self::moveCartToQuickOrder($user->id, $q_id, $item_id);
           }
           else return self::moveCartToQuickOrder($user->id, $q_id);
        }

       return false;
    }

    /**
     * Присваиваем корзине id быстрого заказа
     * @param int $user_id - id пользователя
     * @param int $order_id - id быстрого заказа
     * @param null $id - id товара в корзине
     * @return bool
     * @throws DbException
     */
    public static function moveCartToQuickOrder(int $user_id, int $order_id, $id = null)
    {
        $item = !empty($id) ? 'AND oi.id = :id' : '';
        $user_hash = ($user_id === 2) ? 'AND oi.user_hash = :user_hash' : '';
        $params = [
            ':user_id' => $user_id,
            ':qorder_id' => $order_id
        ];
        if ($user_id === 2) $params[':user_hash'] = $_COOKIE['user'];
        if (!empty($id)) $params[':id'] = $id;
        $sql = "
            UPDATE order_items oi
            SET oi.qorder_id = :qorder_id
            WHERE oi.user_id = :user_id {$user_hash} AND oi.order_id IS NULL AND oi.qorder_id IS NULL {$item}
        ";

        $db = new Db();
        return $db->iquery($sql, $params);
    }
}
