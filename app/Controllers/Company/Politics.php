<?php

namespace Controllers\Company;

use Controllers\Controller;

class Politics extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('company/politics');
    }
}
