<?php

namespace Controllers\Personal;

class Data extends Index
{
    protected function actionDefault()
    {
        $this->view->display('personal/data');
    }
}
