<?php

namespace Controllers\Blog;

use Controllers\Controller;

class News extends Controller
{
    protected function actionDefault()
    {
        var_dump('news');
    }

    protected function actionShow($id)
    {
        var_dump('news show');
        var_dump($id);
    }
}
