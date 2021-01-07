<?php

namespace App\Models;

use App\System\Db;
use App\System\Logger;
use App\Exceptions\DbException;

class UserSession extends Model
{
    protected static $table = 'user_sessions';

    /**
     * Получает сессию пользователя по хэшу сессии
     * @param string $hash
     * @param bool $object
     * @return bool|mixed
     * @throws DbException
     */
    public static function getBySessionHash(string $hash, $object = true)
    {
        $sql = "SELECT * FROM user_sessions WHERE session_hash = :hash";
        $params = [
            ':hash' => $hash
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает сессию пользователя по хэшу куки
     * @param string $hash
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     * @throws DbException
     */
    public static function getByCookieHash(string $hash, $active = false, $object = true)
    {
        $sql = "SELECT * FROM user_sessions WHERE cookie_hash = :hash";
        $sql .= !empty($active) ? ' AND expire > NOW()' : '';
        $params = [
            ':hash' => $hash
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает текущую сессию пользователя
     * @return bool|mixed
     * @throws DbException
     */
    public static function getCurrent()
    {
        if (!empty($_SESSION['session_hash'])) {
            $session = UserSession::getBySessionHash($_SESSION['session_hash']);
        }
        elseif (!empty($_COOKIE['cookie_hash'])) {
            $session = UserSession::getByCookieHash($_COOKIE['cookie_hash'], true);
        }

        return $session ?? false;
    }

    /**
     * Устанавливает текущую сессию пользователя
     * @param int $user_id
     * @param bool $remember
     * @return bool
     * @throws DbException
     */
    public function set(int $user_id, $remember = false)
    {
        $session = new UserSession();
        $session->user_id      = $user_id;
        $session->login        = date("Y-m-d H:i:s");
        $session->ip           = $_SERVER['REMOTE_ADDR'];
        $session->user_agent   = $_SERVER['HTTP_USER_AGENT'];
        $session->session_hash = $_SESSION['session_hash'] = hash('sha256', microtime(true) . uniqid());

        $_SESSION['user'] = User::getFullInfoById($user_id, true, false);

        if (!empty($remember)) {
            $time = 60 * 60 * 24 * DAYS;
            $session->expire      = date("Y-m-d H:i:s", time() + $time);
            $session->cookie_hash = hash('sha256', microtime(true) . uniqid());
            setcookie('cookie_hash', $session->cookie_hash, (time() + $time), '/', SITE, 0);
        }

        if (false === $session->save()) {
            Logger::getInstance()->error(new DbException('Ошибка записи сессии в БД при авторизации пользователя с id = ' . $user_id));
            return false;
        }

        return true;
    }

    /**
     * Продлевает текущую сессию пользователя
     * @param int $user
     * @return bool
     * @throws DbException
     */
    public static function extend($user)
    {
        $_SESSION['user'] = $user;

        $session = new UserSession();
        $session->id           = $user['session_id'];
        $session->user_id      = $user['id'];
        $session->ip           = $_SERVER['REMOTE_ADDR'];
        $session->user_agent   = $_SERVER['HTTP_USER_AGENT'];
        $session->session_hash = $_SESSION['session_hash'] = $user['session_hash'];
        $session->cookie_hash  = $user['cookie_hash'];
        $session->expire       = date("Y-m-d H:i:s", time() + 60 * 60 * 24 * DAYS);

        setcookie('cookie_hash', $session->cookie_hash, (time() + 60 * 60 * 24 * DAYS), '/', SITE, 0);

        if (false === $session->save()) {
            Logger::getInstance()->error(new DbException('Ошибка записи сессии в БД при авторизации пользователя с id = ' . $session->user_id));
            return false;
        }

        return true;
    }

    /**
     * Удаляет текущую сессию пользователя (разлогинивает)
     * @return bool
     * @throws DbException
     */
    public static function deleteCurrent()
    {
        $session = self::getCurrent();

        if (empty($session->id)) {
            Logger::getInstance()->error(new DbException('Не обнаружена текущая сессия для удаления'));
            return false;
        }

        unset($_SESSION['user']);
        unset($_SESSION['session_hash']);
        setcookie('cookie_hash', '', (time() - 1000), '/', SITE, 0);

        $session->session_hash = null;
        $session->cookie_hash = null;
        $session->expire = date("Y-m-d H:i:s", time() - 1);

        if (false === $session->save()) {
            Logger::getInstance()->error(new DbException('Не удалось удалить сессию пользователя с id = ' . $session->user_id));
            return false;
        }

        return true;
    }

    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_user_id($value)
    {
        return (int)$value;
    }

    public function filter_ip($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_user_agent($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_session_hash($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_cookie_hash($text)
    {
        return strip_tags(trim($text));
    }
}
