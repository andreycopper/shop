<?php

namespace App\Controllers\Blog;

use App\Controllers\Controller;

class News extends Controller
{
    protected function actionDefault()
    {
        var_dump('index');die;
    }

    protected function actionShow($id)
    {
        var_dump('show');
        var_dump($id);
        die;
    }

    protected function actionEdit($id)
    {
        var_dump('edit');
        var_dump($id);
        die;
    }

    protected function actionFind()
    {
        var_dump('find');
        die;
    }
}
