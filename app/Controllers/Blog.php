<?php

namespace App\Controllers;

class Blog extends Controller
{
    protected function actionDefault()
    {
        $this->view->display('main', 'help.php');
    }
}
