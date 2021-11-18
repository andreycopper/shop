<?php

namespace Controllers\Personal;

class Subscriptions extends Index
{
    protected function actionDefault()
    {
        $this->view->display('personal/subscriptions');
    }
}
