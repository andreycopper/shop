<?php

namespace Controllers;

use System\Request;
use Models\CallBack;
use Exceptions\DbException;
use Exceptions\UserException;

class CallBacks extends Controller
{
    /**
     * Сохраняет обратный звонок
     * @throws DbException
     * @throws UserException
     */
    protected function actionSave()
    {
        if (Request::isPost()) {
            $form = Request::post();

            if (!CallBack::checkData($form)) self::result(false,'Заполнены не все обязательные поля', [], Request::isAjax());

            $res = CallBack::saveCallback($this->user, $form);
            self::result($res, $res ? 'Заказ обратного звонка отправлен' : 'Ошибка при отправке заказа обратного звонка', [], Request::isAjax());
        }
    }
}
