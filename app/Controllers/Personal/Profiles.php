<?php

namespace Controllers\Personal;

class Profiles extends Index
{
    protected function actionDefault()
    {
        $this->view->display('personal/profiles');
    }
}
