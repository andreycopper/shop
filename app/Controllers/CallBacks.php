<?php

namespace Controllers;

use System\Request;
use Models\CallBack;
use Exceptions\UserException;

class CallBacks extends Controller
{
    /**
     * Сохраняет обратный звонок
     * @throws UserException
     */
    protected function actionSave()
    {
        if (Request::isPost()) {
            $form = Request::post();

            if (CallBack::checkData($form)) {
                $res = CallBack::saveCallback($this->user->id, $form);
                $message = $res ? 'Заказ обратного звонка отправлен' : 'Ошибка при отправке заказа обратного звонка';
            } else $message = 'Заполнены не все обязательные поля';

            self::result($res ?? false,$message);
        }
    }
}
