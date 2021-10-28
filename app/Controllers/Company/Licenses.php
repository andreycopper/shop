<?php

namespace Controllers\Company;

use Controllers\Controller;

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
