<?php


namespace App\Controllers\Company;


use App\Controllers\Controller;

class Index extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('main', 'company.php');
    }
}