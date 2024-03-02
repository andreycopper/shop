<?php
namespace Controllers;

use System\Request;
use Exceptions\DbException;
use Exceptions\UserException;
use Models\User\User as ModelUser;

class Auth extends Controller
{
    protected function before()
    {
        if (User::isAuthorized() && (empty(ROUTE[1]) || !in_array(ROUTE[1], ['Logout']))) {
            header('Location: /personal/');
            die;
        }
    }

    /**
     * Авторизация
     * @throws DbException
     * @throws UserException
     */
    protected function actionDefault()
    {
        if (Request::isPost()) {
            $login = Request::post('login');
            $password = Request::post('password');
            $remember = (bool)Request::post('remember');

            ModelUser::authorize($login, $password, $remember);
        }



        if (Request::isPost()) {
            if (ModelUser::authorization(Request::post('login'), Request::post('password'), (bool)Request::post('remember'))) {
                header('Location: /');
                die;
            }
        } else {
            $this->view->display('auth/auth');
            die;
        }

    }

    /**
     * Регистрация
     * @throws DbException
     * @throws UserException
     */
    protected function actionRegistration()
    {
        if (Request::isPost()) $this->view->register = User::register(Request::post(), Request::isAjax());

        $this->view->display('auth/register');
        die;
    }

    /**
     * Восстановление пароля
     * @throws DbException
     * @throws UserException
     */
    protected function actionRestore()
    {
        if (Request::isPost()) {
            $this->view->restore = User::restore(Request::post(), Request::isAjax());
        }

        $this->view->display('auth/restore');
        die;
    }

    /**
     * Подтверждение регистрации
     */
    protected function actionConfirm()
    {
        $hash = Request::get('hash');
        if (!empty($hash)) $this->view->success = User::confirm($hash);

        $this->view->display('auth/confirm');
        die;
    }

    /**
     * Выход
     * @throws DbException
     */
    protected function actionLogout()
    {
        User::logout();
        header('Location: /');
        die;
    }
}
