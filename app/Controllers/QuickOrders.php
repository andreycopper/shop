<?php

namespace Controllers;

use System\Request;
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

            if (QuickOrder::checkData($form)) {
                $res = (new QuickOrder())->saveOrder($this->user, $form);
                $message = $res ? 'Быстрый заказ отправлен' : 'Ошибка при отправке заказа';
            } else $message = 'Заполнены не все обязательные поля';

            self::result($res ?? false, $message);
        }
    }
}
