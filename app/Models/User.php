<?php

namespace App\Models;

use App\System\Db;
use App\System\Access;
use App\System\Geo;
use App\System\Logger;
use App\System\RSA;
use App\System\Validation;
use App\Exceptions\DbException;
use App\Exceptions\UserException;

/**
 * Class User
 * @package App\Models
 */
class User extends Model
{
    protected static $table = 'users';

    public $id;
    public $active;
    public $blocked;
    public $group_id = 2; // группа "Пользователи"
    public $last_name;
    public $name;
    public $second_name;
    public $email;
    public $phone;
    public $password;
    public $personal_data;
    public $mailing = 1; // подписание на рассылку
    public $mailing_type_id = 2; // тип рассылки html
    public $private_key;
    public $created;
    public $updated;

    /**
     * Получает пользователя с полной информацией по id
     * @param int $id
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     * @throws DbException
     */
    public static function getFullInfoById(int $id, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL' : '';
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
        $params = [
            ':id' => $id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    public static function getByHash(bool $active = true, $object = false)
    {
        $activity =
            !empty($active) ?
                'AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL AND pt.active IS NOT NULL AND tt.active IS NOT NULL' :
                '';

        if (!empty($_SESSION['session_hash'])) {
            $where = 'us.session_hash = :hash';
            $params = [
                ':hash' => $_SESSION['session_hash']
            ];
        }
        elseif (!empty($_COOKIE['cookie_hash'])) {
            if (!empty($active)) $activity .= ' AND us.expire > NOW()';

            $where = 'us.cookie_hash = :hash';
            $params = [
                ':hash' => $_COOKIE['cookie_hash']
            ];
        }
        else return false;

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
            WHERE {$where} {$activity}
            ";

        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает пользователя по номеру телефона
     * @param int $phone
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     * @throws DbException
     */
    public static function getByPhone(int $phone, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL' : '';
        $sql = "
            SELECT u.* 
            FROM users u 
            LEFT JOIN user_groups ug
                ON u.group_id = ug.id
            WHERE u.phone = :phone {$where}
            ";
        $params = [
            ':phone' => $phone
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает пользователя по email
     * @param string $email
     * @param bool $active
     * @param bool $object
     * @return bool|mixed
     * @throws DbException
     */
    public static function getByEmail(string $email, bool $active = false, $object = true)
    {
        $where = !empty($active) ? ' AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL' : '';
        $sql = "
            SELECT u.* 
            FROM users u 
            LEFT JOIN user_groups ug
                ON u.group_id = ug.id
            WHERE u.email = :email {$where}
            ";
        $params = [
            ':email' => $email
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает пользователя по коду восстановления пароля
     * @param string $hash
     * @param bool $active
     * @param bool $object
     * @return false|mixed
     * @throws DbException
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
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает пользователя по коду регистрации
     * @param string $hash
     * @param bool $active
     * @param bool $object
     * @return false|mixed
     * @throws DbException
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
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Генерирует публичный ключ шифрования
     * @return false|string
     */
    public static function generatePublicKey()
    {
        $public_key = RSA::generateRandomBytes(16,true);
        $_SESSION['public_key'] = $public_key;
        return $public_key;
    }

    /**
     * Авторизация пользователя по id
     * @param int $user_id
     * @param bool $remember
     * @return bool
     * @throws DbException
     */
    public static function authorize(int $user_id, bool $remember = false): bool
    {
        $user = $_SESSION['user'] = User::getFullInfoById($user_id, true, false);

        return !empty($user['id']) ?
            (new UserSession())->set($user, $remember) :
            false;
    }

    /**
     * Получает текущего пользователя
     * @return false|mixed|null
     */
    public static function getCurrent()
    {
        $user = self::getByHash();

        //if (!empty($user['cookie_hash'])) UserSession::extend($user);

        return $user ?? null;
    }

    /**
     * Проверяет авторизован ли пользователь
     * @return bool
     * @throws DbException
     */
    public static function isAuthorized()
    {
        $user = self::getCurrent();
        return !empty($user['id']) && !empty($_SESSION['user']['id']) && $user['id'] === $_SESSION['user']['id'];
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
            $exc = new UserException($message);
            Logger::getInstance()->error($exc);
            throw $exc;
        }
    }

    /**
     * Авторизация
     * @param string $login - логин
     * @param string $password - пароль
     * @param bool $remember - запомнить пользователя
     * @param bool $isAjax - авторизация аяксом или нет
     * @return bool
     * @throws DbException
     * @throws UserException
     */
    public static function authorization(string $login, string $password, bool $remember = false, bool $isAjax = false)
    {
        if (!empty($login)) { // не пустой логин
            if (Validation::phone($login)) { // телефон в качестве логина
                $phone = intval(preg_replace('/[^0-9]/', '', $login));
                $phone = mb_strlen($phone) === 11 ? mb_substr($phone, 1) : $phone;
                $user = User::getByPhone($phone, true);
            }
            elseif (Validation::email($login)) { // email в качестве логина
                $email = strip_tags($login);
                $user = User::getByEmail($email, true);
            }
            else { // логин не прошел проверку валидности
                $message = 'Проверьте введенный логин';
                $error = 1;

                if ($isAjax) {
                    Logger::getInstance()->error(new UserException($message));

                    echo json_encode([
                        'result' => false,
                        'message' => $message,
                        'error_code' => $error
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
                if (!empty($password)) { // не пустой пароль
                    if (Validation::password($password)) { // пароль прошел проверку валидности
                        if (password_verify($password, $user->password)) { // верный пароль
                            if (self::authorize($user->id, !empty($form['remember']))) { // успешная авторизация
                                Access::getInstance()->info(new UserException('Пользователь id = ' . $user->id . ' авторизован'));
                                OrderItem::checkAnonymous(); // привязка анонимной корзины к пользователю

                                if ($isAjax) {
                                    echo json_encode(['result' => true]);
                                    die;
                                } else return true;

                            } else { // ошибка авторизации
                                Access::getInstance()->error(new UserException('Ошибка при авторизации пользователя id = ' . $user->id));
                                $message = 'Ошибка при авторизации. Попробуйте попытку позже.';
                                $error = 5;
                            }
                        } else { // неверный пароль
                            Access::getInstance()->error(new UserException('Введен неверный пароль пользователя id = ' . $user->id));
                            $message = 'Неверный пароль';
                            $error = 2;
                        }
                    } else { // пароль не прошел проверку сложности
                        $message = 'Проверьте введенный пароль';
                        $error = 2;
                    }
                } else { // пустой пароль
                    $message = 'Не введен пароль';
                    $error = 2;
                }
            } else { // пользователь не найден
                $message = 'Пользователь не найден';
                $error = 1;
            }
        }
        else { // пустой логин
            $message = 'Не введен логин';
            $error = 1;
        }

        if ($isAjax) {
            Logger::getInstance()->error(new UserException($message));

            echo json_encode([
                'result' => false,
                'message' => $message,
                'error' => $error
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
     * Получает местоположение пользователя по IP
     * @return false|mixed
     * @throws DbException
     */
    public static function getLocation()
    {
        $location = Geo::GetLocationFromIP('213.87.127.224'); // Новосибирск
        //$location = Geo::GetLocationFromIP('87.250.250.242'); // Москва
        //$location = Geo::GetLocationFromIP(Geo::GetUserIP()); // локальный

        return $_SESSION['location'] = City::getByName($location['city'] ?? 'Москва', true, false);
    }

    /**
     * Устанавливает местоположение пользователя по его выбору
     * @param string $city - выбранный город
     * @return false|mixed
     * @throws DbException
     */
    public static function setLocation(string $city)
    {
        return $_SESSION['location'] = City::getByName($city, true, false);
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

    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_group_id($value)
    {
        return (int)$value;
    }

    public function filter_name($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_email($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_phone($value)
    {
        return (int)$value;
    }

    public function filter_password($text)
    {
        return strip_tags(trim($text));
    }
}
