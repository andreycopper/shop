<?php

namespace Controllers;

class Services extends Controller
{
    protected function actionDefault()
    {
        $this->view->display('services');
    }
}
