<?php

namespace Models;

use System\Db;
use Models\User\User;
use System\Validation;
use Exceptions\DbException;
use Exceptions\UserException;

class QuickOrder extends Model
{
    protected static $db_table = 'quick_orders';
    public $id;        // id
    public $user_id;   // id пользователя
    public $user_hash; // хэш пользователя
    public $name;      // имя
    public $phone;     // телефон
    public $created;   // дата создания
    public $updated;   // дата изменения

    /**
     * Сохраняет быстрый заказ
     * @param User $user
     * @param array $form
     * @return bool
     * @throws DbException
     */
    public function saveOrder(User $user, array $form)
    {
        if (!empty($form['id']) && !empty($form['count'])) OrderItem::add($user, $form['id'], $form['count']);

        $cart = OrderItem::getCart($user->id);

        if (!empty($cart)) {
            $qorder = new self();
            $qorder->user_id = $user->id;
            $qorder->user_hash = $_COOKIE['user'];
            $qorder->name = $form['name'];
            $qorder->phone = preg_replace('/[^0-9]/', '', $form['phone']);
            $q_id = $qorder->save();

            if ($q_id) $res = QuickOrder::moveCartToQuickOrder($user->id, $q_id);
        }

        return $res ?? false;
    }

    /**
     * Проверка персональных данных
     * @param array $form
     * @return bool
     */
    public static function checkData(array $form)
    {
        if (!empty($form['agreement']))
            if (!empty($form['name']) && Validation::name(trim($form['name'])))
                if (!empty($form['phone']) && Validation::phone(trim($form['phone'])))
                    return true;

        return false;
    }

    /**
     * Присваиваем корзине id быстрого заказа
     * @param int $user_id - id пользователя
     * @param int $order_id - id быстрого заказа
     * @return bool
     */
    public static function moveCartToQuickOrder(int $user_id, int $order_id)
    {
        $user_hash = ($user_id === 2) ? 'AND oi.user_hash = :user_hash' : '';
        $params = [
            ':user_id' => $user_id,
            ':qorder_id' => $order_id
        ];
        if ($user_id === 2) $params[':user_hash'] = $_COOKIE['user'];
        $sql = "
            UPDATE order_items oi
            SET oi.qorder_id = :qorder_id
            WHERE oi.user_id = :user_id {$user_hash} AND oi.order_id IS NULL AND oi.qorder_id IS NULL
        ";

        $db = Db::getInstance();
        return $db->execute($sql, $params);
    }
}
