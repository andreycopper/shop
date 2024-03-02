<?php
namespace System;

use DateTime;
use Entity\User;
use DateInterval;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Entity\UserSession;
use ReflectionException;
use Exceptions\DbException;
use Exceptions\UserException;
use \Models\User\User as ModelUser;
use System\Loggers\AccessLogger;
use Models\User\UserSession as ModelUserSession;

class Auth
{
    const NOT_AUTHORIZED = 'Not authorized';
    const USER_NOT_FOUND = 'User not found';
    const TOO_MANY_FAILED_ATTEMPTS = 'Too many failed attempts';
    const PASSWORD_ENTERED = 'Password entered';
    const WRONG_TOKEN = 'Wrong token';
    const WRONG_DEVICE = 'Wrong device information';
    const WRONG_SERVICE = 'Wrong service information';
    const WRONG_IP = 'Wrong ip address information';
    const WRONG_LOGIN_PASSWORD = 'Wrong login/password';

    public ?string $token;
    public ?User $user;
    public ?UserSession $userSession;

    /**
     * Вход по паролю
     * @param bool $remember - запоминать ли пользователя
     * @throws ReflectionException
     */
    public function login(bool $remember = false)
    {
        $this->generateSessionToken();
        $this->userSession->comment = self::PASSWORD_ENTERED;

        if ($this->userSession->token && $this->userSession->save()) {
            ModelUserSession::clearFailedAttempts($this->user->getId());
            AccessLogger::getInstance()->info("Пользователь {$this->userSession->login} залогинен. UserId: {$this->userSession->userId}.");

            //$_SESSION['user'] = ModelUser::getById($this->user->getId());
            $_SESSION['user'] = $this->user->toArray(); // TODO проверить как работает...
            $_SESSION['token'] = $this->token;
            if ($remember) setcookie('token', $this->token, time() + ModelUserSession::LIFE_TIME, '/', DOMAIN, 0);

            header('Location: /');
            die;
        }
    }

    /**
     * Генерирует сессионный токен
     */
    private function generateSessionToken()
    {
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
        $lifeTime = ModelUserSession::LIFE_TIME;

        $this->userSession->expire = $date->add(new DateInterval( "PT{$lifeTime}S" ));
        $this->token = $this->userSession->token = (new ModelUserSession())->getToken($this->user, $this->userSession, $timestamp);
    }




























    /**
     * Проверяет авторизацию пользователя (!+)
     * @param $jwt - токен
     * @param $userData - пользовательские данные
     * @return bool
     */
    public static function checkAuthorization($jwt, $userData)
    {
        try {
            $token = JWT::decode($jwt, new Key(ModelUserSession::KEY, 'HS512'));
            $userSession = UserSession::get(['token' => $jwt]);

            if (!empty($userSession->userId)) {
                $user = !empty($_SESSION['user']) ? $_SESSION['user'] : ModelUser::getById($userSession->userId);

                if (!empty($user['id']) && self::checkToken($token ?? null) && self::checkUserData($userData, $token) &&
                    self::checkUserSession($userSession, $token))
                {
                    if (empty($_SESSION['user'])) $_SESSION['user'] = $user;
                    return true;
                } else {
                    ModelUser::logout();
                    return false;
                }
            }
        }
        catch (\Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * Проверяет наличие логина/пароля в данных
     * @param $login - логин
     * @param $password - пароль
     * @return bool
     * @throws UserException
     */
    public static function checkUser($login, $password)
    {
        // TODO добавить проверку регулярками
        if (empty($login) || empty($password)) throw new UserException(self::WRONG_LOGIN_PASSWORD, 401);
        return true;
    }

    /**
     * Проверяет актуальность токена
     * @param Object $token - токен
     * @return bool
     * @throws UserException
     */
    private static function checkToken(Object $token)
    {
        $now = (new DateTime())->getTimestamp();

        if (empty($token) || $token->exp < $now || $token->iat > $now || $token->nbf > $now)
            throw new UserException(self::WRONG_TOKEN);

        return true;
    }

    /**
     * @param array $userData - пользовательские данные
     * @param Object $token - токен
     * @return bool
     * @throws UserException
     */
    private static function checkUserData(array $userData, Object $token)
    {
        if (empty($userData['device']) || $userData['device'] !== $token->data->device ||
            empty($userData['ip']) || $userData['ip'] !== $token->data->ip ||
            empty($userData['serviceId']) || $userData['serviceId'] !== $token->data->serviceId
        ) throw new UserException(self::WRONG_TOKEN);

        return true;
    }

    /**
     * Проверяет соответствие сессии токену
     * @param UserSession|null $userSession - пользовательская сессия
     * @param Object $token - токен
     * @return bool
     * @throws UserException
     */
    private static function checkUserSession(?UserSession $userSession, Object $token)
    {
        if (empty($userSession)) throw new UserException(self::NOT_AUTHORIZED, 401);

        if ($token->iss !== SITE_URL || $token->aud !== $userSession->login ||
            $token->data->id !== $userSession->userId || $token->data->serviceId !== $userSession->serviceId ||
            $token->data->ip !== $userSession->ip || $token->data->device !== $userSession->device
        ) throw new UserException(self::WRONG_TOKEN);

        return true;
    }




}
