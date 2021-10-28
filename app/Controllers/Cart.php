<?php

namespace Controllers;

use System\Request;
use Models\OrderItem;
use Exceptions\DbException;

/**
 * Корзина
 */
class Cart extends Controller
{
    /**
     * Показ корзины
     * @throws DbException
     */
    protected function actionDefault()
    {
        $this->set('cart', OrderItem::getCart($this->user->id));
        $this->set('favorite', [1]);
        $this->view->display('order/cart');
    }

    /**
     * Удаляет товар из корзины
     */
    protected function actionDelete()
    {
        if (Request::isPost()) {
            $res = OrderItem::deleteItem(intval(Request::post('id')), $this->user->id);
            $cart = OrderItem::getCart($this->user->id);
            $message = $res ? 'Товар удален из корзины' : 'Не удалось удалить товар из корзины';

            if (!$res) {
                $cart['message'] .= (!empty($cart['message']) ? "<br>{$message}" : $message);
            }

            $this->set('cart', $cart);
            $this->set('favorite', [1]);
            $this->view->display_element('order/cart');
        }
    }

    /**
     * Очищает корзину
     * @throws DbException
     */
    protected function actionClear()
    {
        if (Request::isPost()) {
            $res = OrderItem::clearCart($this->user->id);
            $cart = OrderItem::getCart($this->user->id);

            $this->set('cart', $cart);
            $this->set('favorite', [1]);
            $this->view->display_element('order/cart');
        }
    }

    /**
     * Пересчет корзины при изменении количества товара
     */
    protected function actionRecalc()
    {
        if (Request::isPost()) {
            $product = Request::post();
            $res = OrderItem::add($this->user, intval($product['id']), intval($product['count']));
            $cart = OrderItem::getCart($this->user->id);

            if (!$res) {
                $cart['message'] .=
                    (!empty($cart['message']) ?
                        '<br>Не удалось добавить товар в корзину' :
                        'Не удалось добавить товар в корзину');
            }

            $this->set('cart', $cart);
            $this->set('favorite', [1]);
            $this->view->display_element('order/cart');
        }
    }
}
