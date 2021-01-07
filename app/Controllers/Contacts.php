<?php

namespace App\Controllers;

class Contacts extends Controller
{
    protected function actionDefault()
    {
        $this->view->display('main', 'contacts.php');
    }
}
