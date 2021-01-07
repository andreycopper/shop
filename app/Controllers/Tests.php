<?php

namespace App\Controllers;

use App\Models\Test;

class Tests extends Controller
{
    protected function before()
    {
    }

    protected function actionDefault()
    {
        Test::fias();
        die;
    }
}