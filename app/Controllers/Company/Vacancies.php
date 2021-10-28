<?php

namespace Controllers\Company;

use Controllers\Controller;

class Vacancies extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('vacancies');
    }
}
