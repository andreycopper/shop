<?php

namespace Controllers\Personal;

class Profile extends Index
{
    protected function actionDefault()
    {
        $this->view->display('personal/profile');
    }
}
