<?php

namespace Controllers\Blog;

use Controllers\Controller;

class Index extends Controller
{
    protected function actionDefault()
    {
        $this->view->display('help');
    }
}
