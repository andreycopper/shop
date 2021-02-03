<?php

namespace App\Controllers;

use App\Exceptions\UserException;
use App\Models\City;
use App\Models\Delivery;
use App\Models\OrderItem;
use App\Models\UserProfile;
use App\Models\Payment;
use App\System\Logger;
use App\System\Request;

class Orders extends Controller
{
    protected function actionDefault()
    {
        $this->view->deliveries = Delivery::getList(true);
        $this->view->payments = Payment::getList(true);
        $this->view->profiles = UserProfile::getListByUser(true);
        $this->view->cart = OrderItem::getCart();

        $this->view->display('order');
    }

    protected function actionFinish()
    {
        var_dump($_POST);

        $profile_id = (new UserProfile())->saveProfile(Request::post(''), $this->view->user['id'] ?? 2, intval(Request::post('type')), Request::isAjax());

        var_dump($profile_id);die;

        if (!empty(Request::post('payment'))) {
            $user_profile->payment = Request::post('payment');
        } else $message = 'Не заполнено поле "Оплата"';











        die;
    }
}
