<?php

namespace Controllers;

use Exceptions\UserException;
use Models\Coupon;
use System\Request;
use Models\OrderItem;
use Exceptions\DbException;

class Cart extends Controller
{
    /**
     * Показ корзины
     * @throws DbException
     */
    protected function actionDefault()
    {
        if (!empty(Request::get('coupon'))) {
            $this->view->coupon = Coupon::get(Request::get('coupon'));
            if (empty($this->view->coupon) || (!empty($this->view->coupon) && !Coupon::check($this->view->coupon)))
                $this->view->coupon_error = true;
        }

        $this->view->cart = OrderItem::getCart($this->view->user->id, Request::get('coupon') ?: '');
        $this->view->favorite = [1];
        $this->view->display('cart');
    }

    /**
     * Удаляет товар из корзины
     */
    protected function actionDelete()
    {
        if (Request::isPost()) {
            OrderItem::deleteItem(intval(Request::post('id')), $this->view->user->id, Request::isAjax());
        }
    }

    /**
     * Очищает корзину
     * @throws DbException
     * @throws UserException
     */
    protected function actionClear()
    {
        if (Request::isPost()) {
            OrderItem::clearCart($this->view->user->id, Request::isAjax());
        }
    }

    /**
     * Пересчет корзины при изменении количества товара
     */
    protected function actionRecalc()
    {
        if (Request::isPost()) {
            $product = Request::post();
            OrderItem::recalc($this->view->user, intval($product['id']), intval($product['count']), Request::isAjax());
        }
    }































    /**
     * Проверяет купон
     */
    protected function actionCheckCoupon()
    {
        if (Request::isPost() && !empty(Request::post('coupon'))) {
            $coupon = Coupon::get(Request::post('coupon'));

            if (!empty($coupon)) Coupon::check($coupon, Request::isAjax());

            if (Request::isAjax()) {
                echo json_encode(['result' => false, 'message' => 'Купон не найден']);
                die;
            }
        }
    }
}
