<?php


namespace App\Controllers\Company;


use App\Controllers\Controller;

class Politics extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('main', 'politics.php');
    }
}