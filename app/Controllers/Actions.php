<?php

namespace Controllers;

class Actions extends Controller
{
    protected function actionDefault()
    {
        $this->view->display('main', 'actions.php');
    }
}
