<?php

namespace Controllers\Company;

use Controllers\Controller;

class Index extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('company/index');
    }
}
