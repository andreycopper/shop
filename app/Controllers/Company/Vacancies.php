<?php


namespace App\Controllers\Company;


use App\Controllers\Controller;

class Vacancies extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('main', 'vacancies.php');
    }
}