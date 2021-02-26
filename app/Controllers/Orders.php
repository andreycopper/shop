<?php

namespace Controllers;

use Models\Order;
use Models\Payment;
use System\Request;
use Models\Delivery;
use Models\OrderItem;
use Models\UserProfile;
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
            $this->view->profiles = UserProfile::getListByUser($this->view->user->id);
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
        $cart = OrderItem::getCart($this->view->user->id);

        if (Order::checkData(Request::post(), $cart, Request::isAjax())) {
            $profile_id = (new UserProfile())->saveProfile(Request::post(), $this->view->user, intval(trim(Request::post('type'))), Request::isAjax());
            $order_id = (new Order())->saveOrder(Request::post(), $cart, $profile_id, Request::isAjax());

            if (Order::moveCartToOrder($this->view->user->id, $order_id, Request::isAjax()))
                header('Location: /orders/success/' . $order_id);
        }
    }

    /**
     * Страница с информацией об успешном оформлении заказа
     * @param $order_id
     * @throws DbException
     * @throws UserException
     */
    protected function actionSuccess($order_id)
    {
        if (is_numeric($order_id)) {
            $this->view->order = Order::getByIdAndUserId(intval($order_id), $this->view->user->id);

            var_dump($this->view->order);
            die;
        }
    }
}
