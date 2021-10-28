<?php

namespace Controllers\Company;

use Controllers\Controller;

class Staff extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('staff');
    }
}
