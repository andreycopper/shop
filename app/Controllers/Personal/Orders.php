<?php

namespace Controllers\Personal;

class Orders extends Index
{
    protected function actionDefault()
    {
        $this->view->display('personal/orders');
    }
}
