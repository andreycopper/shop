<?php

namespace App\Controllers;

use App\Models\City;
use App\Models\Order;
use App\Models\Payment;
use App\System\Request;
use App\Models\Delivery;
use App\Models\OrderItem;
use App\Models\UserProfile;

class Orders extends Controller
{
    /**
     * Страница оформления заказа
     * @throws \App\Exceptions\DbException
     */
    protected function actionDefault()
    {
        $this->view->deliveries = Delivery::getList(true);
        $this->view->payments = Payment::getList(true);
        $this->view->profiles = UserProfile::getListByUser(true);
        $this->view->cart = OrderItem::getCart();

        $this->view->display('order/order');
    }

    /**
     * Завершение заказа
     * @throws \App\Exceptions\DbException
     * @throws \App\Exceptions\UserException
     */
    protected function actionFinish()
    {
        $cart = OrderItem::getCart();

        if (Order::checkData(Request::post(), $cart, Request::isAjax())) {
            $profile_id = (new UserProfile())->saveProfile(Request::post(), $this->view->user['id'] ?? 2, intval(Request::post('type')), Request::isAjax());
            $order_id = (new Order())->saveOrder(Request::post(), $cart, $profile_id, Request::isAjax());

            if (Order::updateCart($cart['items'], $order_id, Request::isAjax())) {
                header('Location: /orders/success/' . $order_id);
            }
        }
    }

    /**
     * Страница с информацией об успешном оформлении заказа
     * @param $order_id
     * @throws \App\Exceptions\DbException
     */
    protected function actionSuccess($order_id)
    {
        if (is_numeric($order_id)) {
            $this->view->order = Order::getByIdAndUserId(
                intval($order_id),
                $this->view->user['id'] ?? 2,
                !$this->view->user['id'] ? $_COOKIE['user'] : null);

            var_dump($this->view->order);
            die;
        }
    }
}
