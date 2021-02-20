<?php

namespace Controllers;

use Models\User;
use System\Request;
use Exceptions\DbException;
use Exceptions\UserException;

class Personal extends Controller
{
    protected function before()
    {
        if (!User::isAuthorized() &&
            (empty(ROUTE[1]) || !in_array(ROUTE[1], ['Change', 'ChangeAjax']))
        ) {
            header('Location: /');
            die;
        }
    }

    protected function actionDefault()
    {
        var_dump('personal');
        die;
    }

    /**
     * Смена пароля
     * @throws DbException
     * @throws UserException
     */
    protected function actionChange()
    {
        if (Request::isPost()) {
            $this->view->success = User::changePassword(Request::post(), Request::isAjax());
        } else {
            if (User::isAuthorized()) {
                $this->view->form = true;
            } else {
                $hash = Request::get('hash');

                if (!empty($hash)) {
                    $user = User::getByRestoreHash($hash, true);

                    if (!empty($user->id))  $this->view->form = true;
                    else $this->view->error = true;
                }
            }
        }

        $this->view->display('personal/change');
        die;
    }
}
