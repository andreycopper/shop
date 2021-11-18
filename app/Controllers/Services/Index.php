<?php

namespace Controllers\Services;

use Controllers\Controller;

class Index extends Controller
{
    protected function actionDefault()
    {
        $this->view->display('services/index');
    }
}
