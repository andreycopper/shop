<?php


namespace App\Controllers\Company;


use App\Controllers\Controller;

class Staff extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('main', 'staff.php');
    }
}