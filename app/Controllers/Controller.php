<?php

namespace Controllers;

use Exceptions\UserException;
use System\Logger;
use Views\View;
use Models\Page;
use Models\User;
use Models\Group;
use System\Request;
use Models\District;
use Models\OrderItem;
use Exceptions\DbException;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

/**
 * Class Controller
 * @package App\Controllers
 */
abstract class Controller
{
    protected $view;
    protected $perPage = 10;

    /**
     * Controller constructor.
     * @throws DbException
     */
    public function __construct()
    {
        $this->view = new View();
        $this->view->current_page = intval(Request::get('page') ?? 1);
        $this->view->page         = Page::getPageInfo(get_class($this)); // информация о странице
        $this->view->public_key   = $_SESSION['public_key'] ?? User::generatePublicKey(); // публичный ключ шифрования
        $this->view->location     = $_SESSION['location'] ?? User::getLocation(); // текущее местоположение
        $this->view->user         = $_SESSION['user'] ?? User::getCurrent(); // текущий пользователь
        $this->view->groups       = $_SESSION['groups'] ?? Group::getCatalog(); // каталог товаров
        $this->view->menu         = $_SESSION['menu'] ?? Page::getMenuTree(true); // меню
        $this->view->cart_count   = OrderItem::getCount(); // количество товаров в корзине
        $this->view->districts    = District::getList(); // список федеральных округов
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
            } else {
                throw new ForbiddenException();
            }
        }
        else {
            throw new NotFoundException();
        }
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
     * Показ успеха
     * @param string $message
     * @param array $data
     * @param bool $isAjax
     * @return bool
     */
    protected static function returnSuccess(string $message = '', array $data = [], bool $isAjax = false)
    {
        if ($isAjax) {
            echo json_encode([
                'result' => true,
                'data' => $data,
                'message' => $message
            ]);
            die;
        }
        else return true;
    }

    /**
     * Показ ошибки
     * @param string $message
     * @param bool $isAjax
     * @throws UserException
     */
    protected static function returnError(string $message, bool $isAjax = false)
    {
        if ($isAjax) {
            Logger::getInstance()->error(new UserException($message));
            echo json_encode([
                'result' => false,
                'message' => $message
            ]);
            die;
        }
        else throw new UserException($message);
    }
}
