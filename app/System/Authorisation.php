<?php

namespace System;

use Models\User\User;
use Models\User\UserSession;
use Exceptions\DbException;
use Exceptions\NotFoundException;

class Authorisation
{
    protected $time;

    public function __construct()
    {
        if (!empty($_POST['remember']) || isset($_COOKIE['REMEMBER'])) {
            $this->time = (60 * 60 * 24 * 7);          // неделя
            $this->setLabelMemorize();
        } else {
            $this->time = (60 * 60);                   // час
        }
    }

    /**
     * Устанавливает пользовательскую сессию
     * @param $user_id
     * @throws DbException
     */
    public function setUserSession($user_id)
    {
        $session = new UserSession();

        $session->user_id      = $user_id;
        $session->cookie_hash  = hash('sha256', microtime(true) . uniqid());
        $session->session_hash = hash('sha256', microtime(true) . uniqid());
        $session->user_agent   = hash('sha256', $_SERVER['HTTP_USER_AGENT']);
        $session->expires      = date("Y-m-d H:i:s", time() + $this->time);

        if (false === $session->save()) {
            $exc = new DbException('Невозможно сохранить запись в БД.');
            Logger::getInstance()->error($exc);
            throw $exc;
        }

        setcookie('USERSESSID', $session->hash, (time() + $this->time), '/');
    }

    /**
     * Получает авторизованного пользователя
     * @return bool|mixed
     * @throws DbException
     * @throws NotFoundException
     */
    public function getCurrentUser()
    {
        $session = UserSession::findSessionByHash($_COOKIE['USERSESSID'] ?? null);

        if (!empty($session)) {

            if ($session->user_agent === hash('sha256', $_SERVER['HTTP_USER_AGENT']) && $session->expires > date("Y-m-d H:i:s")) {

                $user = User::findById($session->user_id);
                if (empty($user)) {
                    $exc = new NotFoundException('Пользователь не найден!');
                    Logger::getInstance()->error($exc);
                    throw $exc;
                }

                $session->expires = date("Y-m-d H:i:s", time() + $this->time);
                if (false === $session->save()) {
                    $exc = new DbException('Невозможно записать изменения в БД.');
                    Logger::getInstance()->error($exc);
                    throw $exc;
                }

                setcookie('USERSESSID', $session->hash, time() + $this->time, '/');
                if (!empty($_COOKIE['REMEMBER'])) {
                    $this->setLabelMemorize();
                }
                return $user;

            } else {
                $this->deleteUserSession($session->hash);
                return false;
            }

        } else {
            $this->deleteCookies();
            return false;
        }
    }

    /**
     * Устанавливает флаг запоминания пользователя
     */
    public function setLabelMemorize()
    {
        setcookie('REMEMBER', 1, time() + $this->time, '/');
    }

    /**
     * Проверяет авторизован ли пользовател
     * @return bool|mixed
     */
    public function isAuthorised() : bool
    {
        if (false !== $this->getCurrentUser()) {
            return true;
        }
        return false;
    }

    /**
     * Удаляет пользовательскую сессию
     * @param $hash
     * @return mixed
     * @throws DbException
     */
    public function deleteUserSession($hash)
    {
        $session = UserSession::findSessionByHash($hash ?? null);
        if (!empty($session)) {
            if (false === $session->delete()) {
                $exc = new DbException('Невозможно удалить запись из БД.');
                Logger::getInstance()->error($exc);
                throw $exc;
            }
        }

        $this->deleteCookies();

        return $session->user_id;
    }

    /**
     * Уничтожает пользовательские куки
     */
    public function deleteCookies()
    {
        setcookie('USERSESSID', null, time() - 1000, '/');
        setcookie('REMEMBER', null, time() - 1000, '/');
    }
}
