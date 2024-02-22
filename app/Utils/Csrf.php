<?php
namespace Utils;

use Entity\User;

class Csrf
{
    /**
     * Возвращает csrf метку
     * @return string
     */
    public static function get(?User $user = null)
    {
        $csrf = $_SESSION['csrf'];

        if (empty($csrf['salt']) || empty($csrf['secret'])) $csrf = self::generate();

        return sha1($csrf['salt'] . ':' . $csrf['secret'] . ':' . session_id() . ($user ? ':' . $user->getLogin() : ''));
    }

    /**
     * Генерирует данные для csrf
     * @return array
     */
    public static function generate()
    {
        $_SESSION['csrf']['salt'] = md5(time());
        $_SESSION['csrf']['secret'] = sha1(time());
        return $_SESSION['csrf'];
    }

    /**
     * Проверяет метку csrf в форме
     * @param string $csrf - csrf
     * @param User|null $user - пользователь
     * @return bool
     */
    public static function check(string $csrf, ?User $user = null)
    {
        return !empty($csrf) && $csrf === self::get($user);
    }
}
