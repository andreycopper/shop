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

            if (!CallBack::checkData($form))
                self::returnError('Заполнены не все обязательные поля', Request::isAjax());


            if (CallBack::saveCallback($this->view->user, $form)) self::returnSuccess('Заказ обратного звонка отправлен', [], Request::isAjax());
            else self::returnError('Ошибка при отправке заказа обратного звонка', Request::isAjax());
        }
    }
}
