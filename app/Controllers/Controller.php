<?php

namespace Controllers;

use Views\View;
use Models\Page;
use Models\Group;
use System\Logger;
use System\Request;
use Models\User\User;
use Models\OrderItem;
use Models\Fias\District;
use Exceptions\DbException;
use Exceptions\UserException;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

/**
 * Class Controller
 * @package App\Controllers
 */
abstract class Controller
{
    protected $user;            // текущий пользователь
    protected $publicKey;       // публичный ключ шифрования
    protected $page_current;    // текущая страница
    protected $page_count = 10; // элементов на странице
    protected $view;

    /**
     * Controller constructor (+)
     * @throws DbException
     */
    public function __construct()
    {
        $this->view = new View();

        $this->user        = $_SESSION['user'] ?? User::getCurrent();              // текущий пользователь
        $this->publicKey   = $_SESSION['public_key'] ?? User::generatePublicKey(); // публичный ключ шифрования
        $this->page_current = intval(Request::get('page') ?? 1);              // текущая страница пагинации

        $this->set('user', $this->user);
        $this->set('publicKey', $this->publicKey);
        $this->set('location', $_SESSION['location'] ?? User::getLocation()); // текущее местоположение
        $this->set('districts', District::get()); // список федеральных округов

        $this->set('page', Page::getPageInfo(URL)); // информация о странице
        $this->set('breadcrumbs', URL); // breadcrumbs
        $this->set('page_current', $this->page_current);

        $this->set('menu', Page::getMenu('main')); // меню
        $this->set('groups', Group::getCatalogMenu()); // каталог товаров
        $this->set('cartCount', OrderItem::getCount($this->user)); // количество товаров в корзине
    }

    /**
     * Проверяет доступ и формирует полное имя action (+)
     * @param string $action
     * @param null $param
     * @throws ForbiddenException|NotFoundException
     */
    public function action(string $action, $param = null)
    {
        if (method_exists($this, $action)) {
            if ($this->access($action)) {
                if (method_exists($this, 'before')) $this->before();
                $this->$action($param ?? null);
                if (method_exists($this, 'after')) $this->after();
                die;
            } else throw new ForbiddenException();
        } else throw new NotFoundException();
    }

    /**
     * Объявляет переменную для View (+)
     * @param $var
     * @param $value
     */
    protected function set($var, $value = null)
    {
        $this->view->$var = $value;
    }

    /**
     * Проверяет доступ к методу в классе $this (+)
     * @param $action - метод, доступ к которому проверяется
     * @return bool
     */
    protected function access($action):bool
    {
        return true;
    }

    /**
     * Возвращает результат в зависимости от запроса
     * TODO перекинуть все ответы на респонз
     * @param bool $result - результат запроса
     * @param string $message - сообщение
     * @param array $data - данные
     * @return bool|void
     * @throws UserException
     */
    protected static function result(bool $result, string $message, array $data = [])
    {
        if (Request::isAjax()) {
            Logger::getInstance()->error(new UserException($message));
            echo json_encode([
                'result' => $result,
                'message' => $message,
                'data' => $data,
            ]);
            die;
        }
        else {
            if (!$result) throw new UserException($message);
            return $result;
        }
    }
}
