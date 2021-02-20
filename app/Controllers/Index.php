<?php

namespace Controllers;

/**
 * Class Index
 * @package App\Controllers
 */
class Index extends Controller
{
    /**
     * Выводит стартовую страницу
     */
    protected function actionDefault()
    {
        $this->view->display('index');
    }
}
