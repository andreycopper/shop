<?php

namespace Controllers\Help;

use Controllers\Controller;

class Index extends Controller
{
    protected function actionDefault()
    {
        $this->view->display('help/index');
    }
}
