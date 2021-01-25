<?php

namespace App\Controllers;

use App\Models\City;
use App\Models\Delivery;
use App\Models\OrderItem;
use App\Models\UserProfile;
use App\Models\Payment;

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
        die;
    }
}
