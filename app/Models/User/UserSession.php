<?php

namespace Models\User;

use System\Db;
use Models\Model;
use System\Logger;
use Exceptions\DbException;

class UserSession extends Model
{
    protected static $db_table = 'user_sessions';

    public $user_id;
    public $login;
    public $ip;
    public $user_agent;
    public $session_hash;
    public $cookie_hash;
    public $expire;

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
        $db = Db::getInstance();
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
        $db = Db::getInstance();
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
     * @param $user
     * @param bool $remember
     * @return bool
     * @throws DbException
     */
    public function set($user, $remember = false)
    {
        $session = new self();
        $session->user_id      = $user->id;
        $session->login        = date("Y-m-d H:i:s");
        $session->ip           = $_SERVER['REMOTE_ADDR'];
        $session->user_agent   = $_SERVER['HTTP_USER_AGENT'];
        $session->session_hash = $_SESSION['session_hash'] = hash('sha256', microtime(true) . uniqid());

        if (!empty($remember)) {
            $time = 60 * 60 * 24 * DAYS;
            $session->expire      = date("Y-m-d H:i:s", time() + $time);
            $session->cookie_hash = hash('sha256', microtime(true) . uniqid());
            setcookie('cookie_hash', $session->cookie_hash, (time() + $time), '/', SITE, 0);
        }

        if (false === $session->save()) {
            Logger::getInstance()->error(new DbException('Ошибка записи сессии в БД при авторизации пользователя с id = ' . $user['id']));
            return false;
        }

        return true;
    }

    /**
     * Продлевает текущую сессию пользователя
     * @param $user
     * @return bool
     * @throws DbException
     */
    public static function extend($user)
    {
        $_SESSION['user'] = $user->toArray();

        $session = new self();
        $session->id           = $user['session_id'];
        $session->user_id      = $user->id;
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

        if (!empty($session->id)) {
            $session->session_hash = null;
            $session->cookie_hash = null;
            $session->expire = date("Y-m-d H:i:s", time() - 1);

            if (false === $session->save()) {
                Logger::getInstance()->error(new DbException('Не удалось удалить сессию пользователя с id = ' . $session->user_id));
                return false;
            }
        } else Logger::getInstance()->error(new DbException('Не обнаружена текущая сессия для удаления'));

        unset($_SESSION['user']);
        unset($_SESSION['session_hash']);
        setcookie('cookie_hash', '', (time() - 1000), '/', SITE, 0);

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
