<?php

namespace Controllers\Personal;

use System\Request;
use Models\User\User;
use Exceptions\DbException;
use Exceptions\UserException;

class Password extends Index
{
    /**
     * Смена пароля
     * @throws DbException
     * @throws UserException
     */
    protected function actionDefault()
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

                    if (!empty($user->id)) $this->view->form = true;
                    else $this->view->error = true;
                }
            }
        }

        $this->view->display('personal/password');
    }
}
