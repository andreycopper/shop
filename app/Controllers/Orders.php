<?php

namespace Controllers;

use Models\Order;
use Models\Payment;
use System\Request;
use Models\Delivery;
use Models\OrderItem;
use Models\User\UserProfile;
use Exceptions\DbException;
use Exceptions\UserException;

class Orders extends Controller
{
    /**
     * Страница оформления заказа
     * @throws DbException
     */
    protected function actionDefault()
    {
        if (!empty($this->view->user) || !empty($_COOKIE['user'])) {
            $this->view->deliveries = Delivery::getList();
            $this->view->payments = Payment::getList();
            $this->view->profiles = UserProfile::getListByUser($this->view->user->id, $_COOKIE['user']);
            $this->view->cart = OrderItem::getCart($this->view->user->id);
            $this->view->display('order/order');
        }
    }

    /**
     * Завершение заказа
     * @throws DbException
     * @throws UserException
     */
    protected function actionFinish()
    {
        $cart = OrderItem::getCart($this->user->id);

        if (Order::checkData(Request::post(), $cart)) {
            $profile_id = (new UserProfile())->saveProfile(Request::post(), $this->user);

            if ($profile_id) {
                $order_id = (new Order())->saveOrder(Request::post(), $cart, $profile_id);

                if ($order_id) {
                    if (Order::moveCartToOrder($order_id, $this->view->user->id, $_COOKIE['user'])) {
                        header("Location: /orders/success/{$order_id}");
                        die;
                    }
                    else $message = 'Не удалось присвоить номер заказа товарам из корзины';
                } else $message = 'Не удалось сохранить заказ пользователя';
            } else $message = 'Не удалось сохранить профиль пользователя';
        } else $message = 'Не заполнены данные в заказе';

        self::result(false, $message);
    }

    /**
     * Страница с информацией об успешном оформлении заказа
     * @param $order_id
     * @throws DbException
     * @throws UserException
     */
    protected function actionSuccess($order_id)
    {
        if (!is_numeric($order_id)) throw new UserException('Заказ не найден!');

        $this->view->order = Order::getByIdUserId(intval($order_id), $this->view->user->id, $_COOKIE['user']);
        $this->view->display('order/success');
    }
}
