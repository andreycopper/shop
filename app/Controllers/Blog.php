<?php

namespace Controllers;

class Blog extends Controller
{
    protected function actionDefault()
    {
        $this->view->display('help');
    }
}
