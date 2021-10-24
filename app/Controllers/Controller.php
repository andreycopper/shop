<?php

namespace Controllers;

use Views\View;
use Models\Page;
use Models\User;
use Models\Group;
use System\Logger;
use System\Request;
use Models\District;
use Models\OrderItem;
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
    protected $user;           // текущий пользователь
    protected $publicKey;      // публичный ключ шифрования
    protected $pageCurrent;    // текущая страница
    protected $pageCount = 10; // элементов на странице
    protected $view;

    /**
     * Controller constructor.
     * @throws DbException
     */
    public function __construct()
    {
        $this->view = new View();

        $this->user        = $_SESSION['user'] ?? User::getCurrent();
        $this->publicKey   = $_SESSION['public_key'] ?? User::generatePublicKey();
        $this->pageCurrent = intval(Request::get('page') ?? 1);

        $this->set('user', $this->user);
        $this->set('publicKey', $this->publicKey);
        $this->set('location', $_SESSION['location'] ?? User::getLocation()); // текущее местоположение
        $this->set('districts', District::getList()); // список федеральных округов

        $this->set('page', Page::getPageInfo(ROUTE)); // информация о странице
        $this->set('breadcrumbs', Page::getBreadCrumbs()); // breadcrumbs
        $this->set('pageCurrent', $this->pageCurrent);

        $this->set('menu', $_SESSION['menu'] ?? Page::getMenuTree(true)); // меню
        $this->set('groups', $_SESSION['groups'] ?? Group::getCatalog()); // каталог товаров
        $this->set('cartCount', OrderItem::getCount()); // количество товаров в корзине
    }

    /**
     * Проверяет доступ и формирует полное имя action
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
                die;
            } else throw new ForbiddenException();
        } else throw new NotFoundException();
    }

    /**
     * Объявляет переменную для View
     * @param $var
     * @param $value
     */
    protected function set($var, $value = null)
    {
        $this->view->$var = $value;
    }

    /**
     * Проверяет доступ к методу в классе $this
     * @param $action - метод, доступ к которому проверяется
     * @return bool
     */
    protected function access($action):bool
    {
        return true;
    }

    /**
     * Возвращает результат в зависимости от запроса
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
