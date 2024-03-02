<?php
namespace Models\User;

use DateTime;
use Models\UserBlock;
use System\Auth;
use System\Db;
use System\Geo;
use System\RSA;
use Models\Model;
use Models\Event;
use System\Access;
use System\Logger;
use System\Response;
use Models\OrderItem;
use Models\Fias\City;
use System\Validation;
use Exceptions\DbException;
use Exceptions\UserException;
use Entity\User as EntityUser;
use Entity\UserSession as EntityUserSession;

/**
 * Class User
 * @package Models\User
 */
class User extends Model
{
    const MAX_COUNT_ATTEMPT = 5;

    protected static $db_table = 'shop.users';

    public int $id;
    public ?int $active = 1;
    public ?int $blocked = null;
    public int $group_id = 4;
    public int $gender_id = 1;
    public string $last_name;
    public string $name;
    public ?string $second_name;
    public string $email;
    public string $phone;
    public string $password;
    public ?int $personal_data_agreement = null;
    public ?int $mailing = null;
    public int $mailing_type_id = 2;
    public int $price_type_id = 2;
    public int $timezone = 0;
    public string $created;
    public ?string $updated = null;

    /**
     * Возвращает пользователя по id
     * @param int $id - id пользователя
     * @return bool|mixed
     */
    public static function getById(int $id, array $params = [])
    {
        $params += ['active' => true];

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['id' => $id];

        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.group_id, u.last_name, u.name, u.second_name, u.email, u.phone, u.password, 
                u.personal_data_agreement, u.mailing, u.mailing_type_id, u.created, u.updated, 
                ug.name AS group_name, ug.price_type_id, u.gender_id, ugn.name gender, 
                pt.name AS price_type, tt.name AS mailing_type, u.timezone 
            FROM users u
            LEFT JOIN user_sessions us ON u.id = us.user_id 
            LEFT JOIN user_groups ug ON u.group_id = ug.id 
            LEFT JOIN price_types pt ON ug.price_type_id = pt.id 
            LEFT JOIN text_types tt ON u.mailing_type_id = tt.id
            LEFT JOIN shop.user_genders ugn ON u.gender_id = ugn.id
            WHERE u.id = :id {$active}";

        $data = $db->query();
        $user = !empty($data) ? array_shift($data) : null;

        if (!empty($user)) $user['price_types'] = UserPriceType::getListByUser($user['id'], $user['group_id']);

        return $user;
    }

    /**
     * Возвращает пользователя по номеру телефона
     * @param string $phone - телефон пользователя
     * @return bool|mixed
     */
    public static function getByPhone(string $phone, array $params = [])
    {
        $params += ['active' => true];

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['phone' => $phone];

        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.group_id, u.last_name, u.name, u.second_name, u.email, u.phone, u.password, 
                u.personal_data_agreement, u.mailing, u.mailing_type_id, u.created, u.updated, 
                ug.name AS group_name, ug.price_type_id, u.gender_id, ugn.name gender, 
                pt.name AS price_type, tt.name AS mailing_type, u.timezone 
            FROM users u
            LEFT JOIN user_sessions us ON u.id = us.user_id 
            LEFT JOIN user_groups ug ON u.group_id = ug.id 
            LEFT JOIN price_types pt ON ug.price_type_id = pt.id 
            LEFT JOIN text_types tt ON u.mailing_type_id = tt.id
            LEFT JOIN shop.user_genders ugn ON u.gender_id = ugn.id
            WHERE u.phone = :phone {$active}";

        $data = $db->query();
        $user = !empty($data) ? array_shift($data) : null;

        if (!empty($user)) $user['price_types'] = UserPriceType::getListByUser($user['id'], $user['group_id']);

        return $user;
    }

    /**
     * Возвращает пользователя по email
     * @param string $email - email пользователя
     * @return bool|mixed
     */
    public static function getByEmail(string $email, array $params = [])
    {
        $params += ['active' => true];

        $db = Db::getInstance();
        $active = !empty($params['active']) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL' : '';
        $db->params = ['email' => $email];

        $db->sql = "
            SELECT 
                u.id, u.active, u.blocked, u.group_id, u.last_name, u.name, u.second_name, u.email, u.phone, u.password, 
                u.personal_data_agreement, u.mailing, u.mailing_type_id, u.created, u.updated, 
                ug.name AS group_name, ug.price_type_id, u.gender_id, ugn.name gender, 
                pt.name AS price_type, tt.name AS mailing_type, u.timezone 
            FROM users u
            LEFT JOIN user_sessions us ON u.id = us.user_id 
            LEFT JOIN user_groups ug ON u.group_id = ug.id 
            LEFT JOIN price_types pt ON ug.price_type_id = pt.id 
            LEFT JOIN text_types tt ON u.mailing_type_id = tt.id
            LEFT JOIN shop.user_genders ugn ON u.gender_id = ugn.id
            WHERE u.email = :email {$active}";

        $data = $db->query();
        $user = !empty($data) ? array_shift($data) : null;

        if (!empty($user)) $user['price_types'] = UserPriceType::getListByUser($user['id'], $user['group_id']);

        return $user;
    }

    public static function authorize($login, $password, $remember): bool
    {
        Auth::checkUser($login, $password);

        $auth = new Auth();

        if (Validation::phone($login)) { // телефон в качестве логина
            $phone = intval(preg_replace('/[^0-9]/', '', $login));
            $phone = mb_strlen($phone) === 11 ? mb_substr($phone, 1) : $phone;

            $auth->user = \Entity\User::get(['phone' => "+7{$phone}"]);
        }
        elseif (Validation::email($login)) { // email в качестве логина
            $email = strip_tags($login);

            $auth->user = \Entity\User::get(['email' => $email]);
        }
        else {
            throw new UserException("Введен невалидный логин. Login: {$login}.");
        }



        if (!empty($auth->user->getId())) { // найден активный пользователь
            $auth->userSession = self::setUserSession($auth->user, $login);
            $countFailedAttempts = UserSession::getCountFailedAttempts($auth->user->getId());

            if ($countFailedAttempts < self::MAX_COUNT_ATTEMPT) { // меньше 5 активных попыток входа






                if (password_verify($password, $auth->user->getPassword())) $auth->login($remember);









                else {
                    $auth->userSession->comment = Auth::WRONG_LOGIN_PASSWORD;
                    $auth->userSession->save();
                    throw new UserException(Auth::WRONG_LOGIN_PASSWORD, 401);
                }

            } else {
                UserSession::clearFailedAttempts($auth->user->getLogin());
                $auth->user->block(UserBlock::INTERVAL_DAY, Auth::TOO_MANY_FAILED_ATTEMPTS);
                throw new UserException(Auth::TOO_MANY_FAILED_ATTEMPTS, 401);
            }
        }
        throw new UserException(Auth::USER_NOT_FOUND, 401);




        $user = self::getFullInfoById($user_id, true, true);
        $_SESSION['user'] = $user;


        return !empty($user->id) && (new UserSession())->set($user, $remember);
    }

    /**
     * Создает пользовательскую сессию
     * @param EntityUser $user
     * @param string $login
     * @return EntityUserSession
     */
    protected static function setUserSession(EntityUser $user, string $login)
    {
        $userSession = new EntityUserSession();
        $userSession->isActive = 1;
        $userSession->login = $login;
        $userSession->userId = $user->getId();
        $userSession->serviceId = EntityUserSession::SERVICE_SITE;
        $userSession->ip = $_SERVER['REMOTE_ADDR'];
        $userSession->device = $_SERVER['HTTP_USER_AGENT'];
        $userSession->logIn = new DateTime();
        $userSession->expire = null;
        $userSession->token = null;
        $userSession->comment = null;
        return $userSession;
    }
























































    /**
     * Генерирует публичный ключ шифрования (+)
     * @return false|string
     */
    public static function generatePublicKey()
    {
        return $_SESSION['public_key'] = RSA::generateRandomBytes(RSA::getIvLength(),true);
    }

    /**
     * Получает местоположение пользователя по IP (+)
     * @return false|mixed
     * @throws DbException
     */
    public static function getLocation()
    {
        $location = Geo::GetLocationFromIP('213.87.126.4'); // Новосибирск
        //$location = Geo::GetLocationFromIP('87.250.250.242'); // Москва
        //$location = Geo::GetLocationFromIP(Geo::GetUserIP()); // локальный

        return $_SESSION['location'] = City::getCityLocationByName($location['city'] ?? 'Москва');
    }

    /**
     * Проверяет авторизован ли пользователь (+)
     * @return bool
     */
    public static function isAuthorized()
    {return false;
        $user = self::getCurrent();
        return !empty($user->id) && $user->id !== 2;
    }

    /**
     * Получает текущего пользователя (+)
     * Неавторизованный пользователь - id=2 Пользователь
     * @return false|mixed|null
     */
    public static function getCurrent()
    {
        $user = self::getByHash() ?: self::getById(2, ['object' => true]);
        $user->price_types = self::getUserPriceTypes($user->group_id, $user->id); // разершенные к просмотру типы цен
        //$_SESSION['user'] = $user;
        //if (!empty($user['cookie_hash'])) UserSession::extend($user);
        return $user ?? null;
    }



    /**
     * Возращает пользователя по session_hash и cookie_hash (+)
     * @param bool $active - возвращать активного/любого пользователя
     * @param bool $object - возвращать объект/массив
     * @return false|mixed
     */
    public static function getByHash(bool $active = true, bool $object = true)
    {
        $activity = !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL' : '';

        if (!empty($_SESSION['session_hash'])) {
            $where = 'us.session_hash = :hash';
            $params = ['hash' => $_SESSION['session_hash']];
        }
        elseif (!empty($_COOKIE['cookie_hash'])) {
            if (!empty($active)) $activity .= ' AND us.expire > NOW()';

            $where = 'us.cookie_hash = :hash';
            $params = ['hash' => $_COOKIE['cookie_hash']];
        }
        else return false;

        $sql = "
            SELECT 
                u.id, u.active, u.blocked, u.group_id, u.last_name, u.name, u.second_name, u.email, u.phone, u.password, 
                u.personal_data, u.mailing, u.mailing_type_id, IFNULL(u.price_type_id , ug.price_type_id) price_type_id, 
                u.private_key, u.created, u.updated, 
                ug.name AS group_name, 
                pt.name AS price_type,
                tt.name AS mailing_type 
            FROM users u
            LEFT JOIN user_sessions us 
                ON u.id = us.user_id 
            LEFT JOIN user_groups ug
                ON u.group_id = ug.id 
            LEFT JOIN price_types pt
                ON ug.price_type_id = pt.id 
            LEFT JOIN text_types tt 
                ON u.mailing_type_id = tt.id
            WHERE {$where} {$activity}
            ";

        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Возвращает список доступных для просмотра типов цен (+)
     * @param int $group_id - группа пользователя
     * @param int $user_id - id пользователя
     * @return array
     */
    public static function getUserPriceTypes(int $group_id, int $user_id)
    {
        $db = Db::getInstance();
        $db->params = [
            'group_id' => $group_id,
            'user_id' => $user_id,
        ];
        $db->sql = "SELECT price_type_id FROM shop.user_price_types WHERE user_group_id = :group_id OR user_id = :user_id";
        $data = $db->query();

        $res = [2];
        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                if (!in_array($item['price_type_id'], $res)) $res[] = intval($item['price_type_id']);
            }
        }
        return $res;
    }






















































    /**
     * Получает пользователя с полной информацией по id
     * @param int $id
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     */
    public static function getFullInfoById(int $id, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL' : '';
        $params = ['id' => $id];
        $sql = "
            SELECT 
                u.id, u.active, u.blocked, u.group_id, u.last_name, u.name, u.second_name, u.email, u.phone, u.password, 
                u.personal_data, u.mailing, u.mailing_type_id, u.private_key, u.created, u.updated, 
                ug.name AS group_name, ug.price_type_id, 
                pt.name AS price_type,
                tt.name AS mailing_type 
            FROM users u
            LEFT JOIN user_sessions us
                ON u.id = us.user_id 
            LEFT JOIN user_groups ug
                ON u.group_id = ug.id 
            LEFT JOIN price_types pt
                ON ug.price_type_id = pt.id 
            LEFT JOIN text_types tt 
                ON u.mailing_type_id = tt.id 
            WHERE u.id = :id {$where}
            ";
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает пользователя по коду восстановления пароля
     * @param string $hash
     * @param bool $active
     * @param bool $object
     * @return false|mixed
     */
    public static function getByRestoreHash(string $hash, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL AND urs.expire > NOW()' : '';
        $sql = "
            SELECT 
                u.id, u.active, u.group_id, u.name, u.last_name, u.email, u.phone 
            FROM users u
            LEFT JOIN user_restore_requests urs
                ON u.id = urs.user_id 
            LEFT JOIN user_groups ug
                ON u.group_id = ug.id 
            WHERE urs.hash = :hash {$where}
            ";
        $params = [
            ':hash' => $hash
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает пользователя по коду регистрации
     * @param string $hash
     * @param bool $active
     * @param bool $object
     * @return false|mixed
     */
    public static function getByConfirmHash(string $hash, bool $active = true, $object = true)
    {
        $activity = !empty($active) ? 'AND u.blocked IS NULL AND ug.active IS NOT NULL AND urr.expire > NOW()' : '';
        $sql = "
            SELECT 
                u.* 
            FROM users u
            LEFT JOIN user_register_requests urr
                ON u.id = urr.user_id 
            LEFT JOIN user_groups ug
                ON u.group_id = ug.id
            WHERE urr.hash = :hash AND u.active IS NULL {$activity}
            ";
        $params = [
            ':hash' => $hash
        ];
        $db = Db::getInstance();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }



    /**
     * Выход
     * @return bool
     * @throws DbException
     */
    public static function logout(): bool
    {
        return UserSession::deleteCurrent();
    }

    /**
     * Регистрация
     * @param array $form
     * @param bool $isAjax
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function register(array $form, bool $isAjax = false)
    {
        if (!empty($form['personal_data'])) { // получено согласие на обработку персональных данных
            if (!empty($form['last_name'])) { // получена фамилия
                if (Validation::name($form['last_name'])) { // фамилия прошла проверку валидности
                    if (!empty($form['name'])) { // получено имя
                        if (Validation::name($form['name'])) { // имя прошло проверку валидности
                            if (!empty($form['email'])) { // получен email
                                if (Validation::email($form['email'])) { // email прошел проверку валидности
                                    $email = strip_tags(trim($form['email']));
                                    $user = self::getByEmail($email);

                                    if (empty($user)) { // не найден пользователь с таким email
                                        if (!empty($form['phone'])) { //получен номер телефона
                                            if (Validation::phone($form['phone'])) { // телефон прошел проверку валидности
                                                $phone = preg_replace('/[^0-9]/', '', $form['phone']);
                                                $phone = mb_strlen($phone) === 11 ? mb_substr($phone, 1) : $phone;
                                                $user = self::getByPhone($phone);

                                                if (empty($user)) { // не найден пользователь с таким номером телефона
                                                    if (!empty($form['password']) && !empty($form['password_confirm'])) { // получен пароль и его подтверждение
                                                        if (Validation::password($form['password'])) { // пароль прошел проверку сложности
                                                            if ($form['password'] === $form['password_confirm']) { // пароль и его подтверждение совпадают
                                                                $private_key = RSA::generateRandomBytes(0, true);
                                                                $rsa = new RSA($private_key);

                                                                $user = new self();
                                                                $user->active = null;
                                                                $user->private_key = $private_key;
                                                                $user->last_name = $rsa->encrypt(strip_tags(trim($form['last_name'])));
                                                                $user->name = $rsa->encrypt(strip_tags(trim($form['name'])));
                                                                $user->second_name = !empty($form['second_name']) ? $rsa->encrypt(strip_tags(trim($form['second_name']))) : null;
                                                                $user->email = $email;
                                                                $user->phone = $phone;
                                                                $user->password = password_hash($form['password'], PASSWORD_DEFAULT);
                                                                $user->personal_data = !empty($form['personal_data']) ? 1 : null;
                                                                $user->created = date('Y-m-d H:i:s');
                                                                $result = $user->save(); // сохраняем пользователя

                                                                if ($result) { // получен id пользователя
                                                                    $request = UserRegisterRequest::create($result);

                                                                    if ($request) { // запрос на регистрацию создан
                                                                        $event = Event::create(
                                                                            $result,
                                                                            Event::TEMPLATE_REGISTRATION_CONFIRM,
                                                                            Event::TYPE_MAIL,
                                                                            [
                                                                                'hash' => $request
                                                                            ]
                                                                        );

                                                                        if ($event) { // событие отправки письма создано
                                                                            if ($isAjax) {
                                                                                echo json_encode(['result' => true]);
                                                                                die;
                                                                            } else return true;
                                                                        } else {
                                                                            if (!$user->delete()) Logger::getInstance()->error(new DbException('Ошибка удаления созданного пользователя с id = ' . $result));
                                                                            $message = 'Ошибка при отправке подтверждающего письма. Попробуйте зарегистрироваться позже';
                                                                        }
                                                                    } else {
                                                                        if (!$user->delete()) Logger::getInstance()->error(new DbException('Ошибка удаления созданного пользователя с id = ' . $result));
                                                                        $message = 'Ошибка при создании запроса на регистрацию. Попробуйте позже';
                                                                    }
                                                                } else $message = 'Не удалось зарегистрировать пользователя. Попробуйте позже.';
                                                            } else $message = 'Пароль и его подтверждение не совпадают';
                                                        } else $message = 'Недостаточная сложность пароля';
                                                    } else $message = 'Не введены пароль или подтверждение пароля';
                                                } else $message = 'Пользователь с таким номером телефона уже зарегистрирован';
                                            } else $message = 'Недопустимые символы в номере телефона';
                                        } else $message = 'Не введен номер телефона';
                                    } else $message = 'Пользователь с таким email уже зарегистрирован';
                                } else $message = 'Недопустимые символы в email';
                            } else $message = 'Не введен email';
                        } else $message = 'Недопустимые символы в имени';
                    } else $message = 'Не введено имя';
                } else $message = 'Недопустимые символы в фамилии';
            } else $message = 'Не введена фамилия';
        } else $message = 'Не получено согласие на обработку персональных данных';

        if ($isAjax) {
            Logger::getInstance()->error(new UserException($message));

            echo json_encode([
                'result' => false,
                'message' => $message
            ]);
            die;
        }
        else {
            throw new UserException($message);
        }
    }

    /**
     * Авторизация
     * @param string $login - логин
     * @param string $password - пароль
     * @param bool $remember - запомнить пользователя
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function authorization(string $login, string $password, bool $remember = false)
    {
        if (!empty($login)) { // не пустой логин
            if (Validation::phone($login)) { // телефон в качестве логина
                $phone = intval(preg_replace('/[^0-9]/', '', $login));
                $phone = mb_strlen($phone) === 11 ? mb_substr($phone, 1) : $phone;
                $user = User::getByPhone($phone);
            }
            elseif (Validation::email($login)) { // email в качестве логина
                $email = strip_tags($login);
                $user = User::getByEmail($email);
            }
            else {
                Access::getInstance()->error("Введен невалидный логин. Login: {$login}.");
                throw new UserException('Введен неверный логин или пароль.');
            }

            if (!empty($user->id)) { // найден пользователь
                if (!empty($password)) { // не пустой пароль
                    if (Validation::password($password)) { // пароль прошел проверку валидности
                        if (password_verify($password, $user->password)) {// верный пароль
                            if (self::authorize($user->id, $remember)) { // успешная авторизация
                                Access::getInstance()->info("Пользователь авторизован. UserId: {$user->id}.");
                                OrderItem::checkAnonymous(); // привязка анонимной корзины к пользователю
                                Response::result(true);
                            } else { // ошибка авторизации
                                Access::getInstance()->error('Ошибка при авторизации. UserId: ' . $user->id);
                                throw new UserException('Ошибка при авторизации. Попробуйте попытку позже.');
                            }
                        } else Access::getInstance()->error("Введен неверный пароль. UserId: {$user->id}.");
                    } else Access::getInstance()->error("Введен невалидный пароль. UserId: {$user->id}.");
                } else Access::getInstance()->error("Не введен пароль. UserId: {$user->id}.");
            } else Access::getInstance()->error("Пользователь не найден. Login: {$login}.");
        } else Access::getInstance()->error("Не введен логин.");

        throw new UserException('Введен неверный логин или пароль.');
    }

    /**
     * Восстановление пароля
     * @param array $form
     * @param bool $isAjax
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function restore(array $form, bool $isAjax = false)
    {
        if (!empty($form['login'])) { // не пустой логин
            if (Validation::phone($form['login'])) { // телефон в качестве логина
                $phone = intval(preg_replace('/[^0-9]/', '', $form['login']));
                $phone = mb_strlen($phone) === 11 ? mb_substr($phone, 1) : $phone;
                $user = User::getByPhone($phone, true);
            }
            elseif (Validation::email($form['login'])) { // email в качестве логина
                $email = strip_tags($form['login']);
                $user = User::getByEmail($email, true);
            }
            else { // логин не прошел проверку валидности
                $message = 'Проверьте введенный логин';

                if ($isAjax) {
                    Logger::getInstance()->error(new UserException($message));

                    echo json_encode([
                        'result' => false,
                        'message' => $message
                    ]);
                    die;
                }
                else {
                    $exc = new UserException($message);
                    Logger::getInstance()->error($exc);
                    throw $exc;
                }
            }

            if (!empty($user->id)) { // найден пользователь
                $request = UserRestoreRequest::create($user->id);

                if ($request) { // запрос на восстановление создан
                    $event = Event::create(
                        $user->id,
                        Event::TEMPLATE_RESTORE,
                        Event::TYPE_MAIL,
                        [
                            'hash' => $request
                        ]
                    );

                    if ($event) {
                        if ($isAjax) {
                            echo json_encode(['result' => true]);
                            die;
                        } else return true;
                    } else $message = 'Ошибка при отправке письма. Попробуйте позже';
                } else $message = 'Ошибка при создании запроса на изменение пароля. Попробуйте позже';
            } else $message = 'Пользователь не найден';
        } else $message = 'Не введен логин';

        if ($isAjax) {
            Logger::getInstance()->error(new UserException($message));

            echo json_encode([
                'result' => false,
                'message' => $message
            ]);
            die;
        }
        else {
            $exc = new UserException($message);
            Logger::getInstance()->error($exc);
            throw $exc;
        }
    }

    /**
     * Смена пароля
     * @param array $form
     * @param bool $isAjax
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function changePassword(array $form, bool $isAjax = false)
    {
        if (Validation::password($form['password'])) { // пароль прошел проверку валидности
            if ($form['password'] === $form['password_confirm']) { // пароль и его подтверждение совпадают
                if (User::isAuthorized()) {
                    $user = self::getFullInfoById($_SESSION['user']['id'], true);
                } elseif (!empty($form['hash'])) { // получен код восстановления пароля
                    $user = self::getByRestoreHash($form['hash'], true);
                } else {
                    $message = 'Не получен код восстановления';

                    if ($isAjax) {
                        Logger::getInstance()->error(new UserException($message));

                        echo json_encode([
                            'result' => false,
                            'message' => $message
                        ]);
                        die;
                    } else {
                        $exc = new UserException($message);
                        Logger::getInstance()->error($exc);
                        throw $exc;
                    }
                }

                if (!empty($user->id)) { // найден пользователь по коду восстановления
                    $user->password = password_hash($form['password'], PASSWORD_DEFAULT);

                    if ($user->save()) { // новый пароль сохранен
                        if (!empty($form['hash'])) { // обновляем запрос, если был отправлен код восстановления
                            $request = UserRestoreRequest::getByHash($form['hash']);
                            $request->expire = date("Y-m-d H:i:s", time() - 1);

                            if (false === $request->save()) {
                                Logger::getInstance()->error(new DbException('Ошибка деактивации кода запроса на смену пароля пользователя с id = ' . $user->id));
                            }
                        }

                        if ($isAjax) {
                            echo json_encode(['result' => true]);
                            die;
                        } else return true;
                    } else $message = 'Ошибка при сохранении пароля. Попробуйте позже.';
                } else $message = 'Пользователь не найден';
            } else $message = 'Пароль и его подтверждение не совпадают';
        } else $message = 'Сложность пароля недостаточна';

        if ($isAjax) {
            Logger::getInstance()->error(new UserException($message));

            echo json_encode([
                'result' => false,
                'message' => $message
            ]);
            die;
        }
        else {
            $exc = new UserException($message);
            Logger::getInstance()->error($exc);
            throw $exc;
        }
    }

    /**
     * Подтверждение регистрации
     * @param string $hash
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function confirm(string $hash)
    {
        $user = User::getByConfirmHash($hash, true);

        if (!empty($user->id)) {
            $user->active = 1;

            if (!$user->save()) {
                $exc = new UserException('Не удалось активировать пользователя. Попробуйте позже.');
                Logger::getInstance()->error($exc);
                throw $exc;
            }

            $event = Event::create(
                $user->id,
                Event::TEMPLATE_REGISTRATION,
                Event::TYPE_MAIL
            );

            if ($event) {
                $request = UserRegisterRequest::getByHash($hash);
                $request->expire = date("Y-m-d H:i:s", time() - 1);

                if (!$request->save()) Logger::getInstance()->error(new DbException('Ошибка деактивации кода запроса на активацию пользователя с id = ' . $user->id));

                return true;

            } else Logger::getInstance()->error(new DbException('Не удалось создать событие орегистрации пользователя с id = ' . $user->id));
        }
        else {
            $exc = new UserException('Не найден пользователь для активации.');
            Logger::getInstance()->error($exc);
            throw $exc;
        }
    }

    /**
     * Устанавливает местоположение пользователя по его выбору
     * @param string $city - выбранный город
     * @return false|mixed
     * @throws DbException
     */
    public static function setLocation(string $city)
    {
        return $_SESSION['location'] = City::getByName($city, true);
    }

    /**
     * Получает город пользователя (Москва по умолчанию)
     * @return mixed
     * @throws DbException
     */
    public static function getCity()
    {
        return self::getLocation()['city'];
    }
}
