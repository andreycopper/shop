<?php

namespace Controllers\Personal;

use Models\Page;
use Models\User\User;
use Controllers\Controller;

class Index extends Controller
{
    protected function before()
    {
        // редирект неавторизованного пользователя на главную
        if (!User::isAuthorized() && (empty(ROUTE[1]) || !in_array(ROUTE[1], ['Password', 'PasswordAjax']))) {
            header('Location: /');
            die;
        }

        $this->set('menu_personal', Page::getMenu('personal')); // боковое меню
    }

    protected function actionDefault()
    {
        $this->view->display('personal/index');
    }
}
