<?php

namespace Controllers\Blog;

use Controllers\Controller;

class Articles extends Controller
{
    protected function actionDefault()
    {
        var_dump('index');
    }

    protected function actionShow($id)
    {
        var_dump('show');
        var_dump($id);
    }
}
