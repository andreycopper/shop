<?php
namespace Models\User;

use Firebase\JWT\JWT;
use System\Db;
use Models\Model;
use System\Logger;
use Exceptions\DbException;
use Entity\User as EntityUser;
use Entity\UserSession as EntityUserSession;

class UserSession extends Model
{
    const SERVER = SITE_URL; // сервер токена
    const SERVICE_MOBILE = 1; // сервис мобильный
    const SERVICE_SITE = 2; // сервис сайт
    const SERVICES = [self::SERVICE_MOBILE, self::SERVICE_SITE]; // сервисы, использующие авторизацию
    const LIFE_TIME = 60 * 60 * 24 * AUTH_DAYS; // время жизни токена
    const KEY = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCVJy2Rg8HeBqFQ'.
    'fXLDcEhSHobfrkz3I9FqFS1z01q4pJkCC2FLxwyJPdmNFCXo/s1a+D6/20RZmmvr'.
    'Ma3nzrcs2n7aNpbTIDxXT0XeycWBm+EF5sWb4aMlBUnbHJPKcKb+bN7uynrRq5r4'.
    'WgnPHLjCNCeNxMfvi7d/6Qtq3RuhRLkTs/2uEFh0TrQIsAq/PFTFqTpensMBCSy4'.
    'cASSOdujZrTMckay5yTzMOKp7T0ALljsAs0GPRkOWJu++Z1YB4DiUeuqTAxrp4UN'.
    'qDc+Hye6fVekSHfY1AQcqwMTB33M3ksJXIEw4r3uXqOcOmakzXNt///rhfpSFvRW'.
    'oR29azgxAgMBAAECggEAJvAMaGGiOek3McEeNcFZT/7iLQDe3OH/6JbQv90cYPmv'.
    'bCY2Z0b2kdmQstDguLvUNnx6PTHr3QyAQe09PjfsAAymcycvJrzSo+RxFCFOq3Bb'.
    'RWQikHhOU/rWdho2xvGz1tXrOSGpAJWxJkazKtuPrDtbXBpUK8goxn67WVGRxf65'.
    '322gtcc/g4zcdvgXtklb9Sln1vdxKrmssj/3W0kGq2XvXriJbRVB8dDPeV9hvP8S'.
    'XGeABnjkkDb2WBNRqgr3o8iMJWBeUuzdYn7Q6m+Oyng10wRzzX/vhhD4LR0s7rnz'.
    '99bYBAcuSqImUP6Z1acKOdrPM+N0LqvD9ZoHWOelQQKBgQDESoLvkD5XpkAZCW4V'.
    'XBhTN2qaUEbA4KhzuopV/D/IgAiG+er1hnWeE4kvAPiGSm7nvWLYBjcGXwDxqJti'.
    'zjHh1+clPS/UQQttAIRAYwNOQvIzVrY08ww4BnAkU6CQh3KFvSYg0IN6tpc1Cj46'.
    'y6BQ/4afmsxhF94bfRlsbPTGuQKBgQDChfS1L5ygTB0ehf12lgsW1wxwjupnNpXK'.
    'J8S1rKt5s89/E3Q1EGSNub4oINsM+vQ5z/TswYZk54UsUuIOOleX94vMm2XPtMYx'.
    '4wgsC5EFO6Wm0iNrQNGNe2qkmcPgJnMTwE/vbG3kOKe9xEL2l3YQvzpxBLf7Qyuv'.
    'bU29/SZBOQKBgQCoCcGdpDY6grBMvq3myzhnxQEVqbNoWuraZ88VXSSdWD30ju59'.
    '0eXOtZqzCnm3PPFEofSESo3AfoQoXNbo9uvtEw9l6cOQST6mydJt7FVgIh+Fo63I'.
    'FmlXbOuDrbO/BrUbmJmTbe2gl79KQMKVQsyzioyNBdABLpWNosKo9310wQKBgDwt'.
    '2wXOxALnaT7PLxnn02hugT+1RxlFTtPqt7WIxMfy8+eZaiMcfi9GXmjluT7ryHC2'.
    'QEyalmxTH+UVgy/ppr2x7MMQ9E9s2sAGP7n4nhXjXR0d960vsWS24Mgpdeq7mnBq'.
    '14/3mIu5Z1OTCzBkrTcDIh5i2lRWdIZiJ2H2lkYpAoGAO51GRrl+JA13KiNVmJ2R'.
    'vuwJ5/x8V+lMsMZZWqwO0280j8+0EBGx51RzchNjXk6Ou/yE5JVj57Yf4UgSSjpP'.
    '2FNDEXFjPnsoggTxK1g4PtbAnfgKRjkwq2aq27phuIVMXcN6m7ZzhYEFdUlG0S5+'.
    'YVtxgNU+T6sTvs6sl/5BVCU=';

    protected static $db_table = 'shop.user_sessions';

    public int $id;
    public ?int $active = null;
    public string $login;
    public int $user_id = 2;
    public int $service_id = 2;
    public string $ip;
    public string $device;
    public string $log_in;
    public ?string $expire = null;
    public ?string $token = null;
    public ?string $comment = null;

    /**
     * Возвращает количество неудачных попыток залогиниться
     * @param $user_id
     * @return int
     */
    public static function getCountFailedAttempts($user_id)
    {
        $db = Db::getInstance();
        $db->params = ['user_id' => $user_id];
        $db->sql = "
            SELECT count(id) count 
            FROM shop.user_sessions 
            WHERE token IS NULL AND active IS NOT NULL AND user_id = :user_id";

        $res = $db->query();
        return $res[0]['count'] ?? 0;
    }

    /**
     * Очищает неудачные попытки залогиниться (!+)
     * @param $user_id
     * @return array
     */
    public static function clearFailedAttempts($user_id)
    {
        $db = Db::getInstance();
        $db->params = ['user_id' => $user_id];
        $db->sql = "UPDATE shop.user_sessions SET active = NULL WHERE user_id = :user_id AND token IS NULL";
        return $db->query();
    }

    /**
     * Генерирует токен для пользователя
     * @param EntityUser $user - пользователь
     * @param EntityUserSession $userSession - сессия пользователя
     * @param int $timeStamp - метка времени
     * @return string
     */
    public function getToken(EntityUser $user, EntityUserSession $userSession, int $timeStamp)
    {
        $data = [
            "iss" => SITE_URL, // адрес или имя удостоверяющего центра
            "aud" => $user->getEmail(), // имя клиента для которого токен выпущен
            "iat" => $timeStamp, // время, когда был выпущен JWT
            "nbf" => $timeStamp, // время, начиная с которого может быть использован (не раньше, чем)
            "exp" => $timeStamp + self::LIFE_TIME, // время истечения срока действия токена
            "data" => [
                "id"         => $userSession->getUserId(),
                "serviceId"  => $userSession->getServiceId(),
                "ip"         => $userSession->getIp(),
                "device"     => $userSession->getDevice(),
                "expired"    => $userSession->getExpire()
            ]
        ];

        return JWT::encode($data, self::KEY, 'HS512');
    }









































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
}
