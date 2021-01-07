<?php


namespace App\Controllers\Company;


use App\Controllers\Controller;

class Licenses extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('licenses');
    }
}