<?php

namespace Controllers;

class Help extends Controller
{
    protected function actionDefault()
    {
        $this->view->display('help');
    }
}
