<?php

namespace Controllers\Personal;

class Accounts extends Index
{
    protected function actionDefault()
    {
        $this->view->display('personal/accounts');
    }
}
