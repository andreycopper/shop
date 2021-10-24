<?php

namespace Controllers;

use System\Request;
use Models\OrderItem;
use Models\QuickOrder;
use Exceptions\DbException;
use Exceptions\UserException;

class QuickOrders extends Controller
{
    /**
     * Сохраняет быстрый заказ
     * @throws DbException
     * @throws UserException
     */
    protected function actionSave()
    {
        if (Request::isPost()) {
            $form = Request::post();

            if (empty($form['id']) && empty($form['count'])) $cart = OrderItem::getCart($this->view->user->id);

            if (!QuickOrder::checkData($form))
                self::result(false, 'Заполнены не все обязательные поля', [], Request::isAjax());

            if (!QuickOrder::checkProducts($form, $cart ?? null))
                self::result(false, 'Не найдены товары для быстрого заказа', [], Request::isAjax());

            $res = QuickOrder::saveOrder($this->view->user, $form);
            self::result($res, $res ? 'Быстрый заказ отправлен' : 'Ошибка при отправке заказа', [], Request::isAjax());
        }
    }
}
