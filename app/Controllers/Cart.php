<?php

namespace App\Controllers;

use App\Models\Coupon;
use App\System\Request;
use App\Models\OrderItem;
use App\Exceptions\DbException;

class Cart extends Controller
{
    protected function actionDefault()
    {
        if (!empty(Request::get('coupon'))) {
            $this->view->coupon = Coupon::get(Request::get('coupon'));

            if (empty($this->view->coupon) || (!empty($this->view->coupon) && !Coupon::check($this->view->coupon)))
                $this->view->coupon_error = true;
        }

        $this->view->cart = OrderItem::getCart(Request::get('coupon') ?? '');
        $this->view->favorite = [1];

        $this->view->display('cart');
    }

    /**
     * Проверяет купон
     */
    protected function actionCheckCoupon()
    {
        if (Request::isPost()) {
            $coupon = Coupon::get(Request::post('coupon'));

            if (!empty($coupon)) Coupon::check($coupon, Request::isAjax());

            echo json_encode([
                'result' => false,
                'message' => 'Купон не найден'
            ]);
            die;
        }
    }

    /**
     * Пересчет корзины при изменении количества товара (ajax)
     */
    protected function actionRecalcProduct()
    {
        if (Request::isPost()) {
            $product = Request::post();
            OrderItem::recalc(intval($product['id']), intval($product['count']), intval($product['price_type']), Request::isAjax());
        }
    }

    /**
     * Удаляет товар из корзины (ajax)
     */
    protected function actionDeleteProduct()
    {
        if (Request::isPost() && Request::isAjax()) {
            $result = OrderItem::deleteItem(intval(Request::post('id')));

            echo json_encode($result);
            die;
        }
    }

    /**
     * Очищает корзину (ajax)
     * @throws DbException
     */
    protected function actionClear()
    {
        if (Request::isPost() && Request::isAjax()) {
            $result = OrderItem::clearCart();

            echo json_encode($result);
            die;
        }
    }
}