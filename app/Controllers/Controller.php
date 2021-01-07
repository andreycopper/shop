<?php

namespace App\Controllers;

use App\Models\City;
use App\Views\View;
use App\Models\Page;
use App\Models\User;
use App\Models\Group;
use App\Models\Region;
use App\System\Logger;
use App\System\Request;
use App\Models\District;
use App\Models\OrderItem;
use App\Exceptions\DbException;
use App\Exceptions\NotFoundException;

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
        $class = explode('\\', mb_strtolower(get_class($this)));
        setcookie('user', $_COOKIE['user'] ?? hash('sha256', microtime(true) . uniqid()), time() + 60 * 60 * 24 * 365, '/', SITE, 0);
        setcookie('page', $_SERVER['REQUEST_URI'] ?? '/', time() + 60 * 60 * 24 * 365, '/', SITE, 0);

        $this->view = new View();
        $this->view->page         = Page::getPageInfo(array_pop($class));
        $this->view->city         = $_SESSION['location']['city'] ?? User::getCity();
        $this->view->user         = $_SESSION['user'] ?? User::getCurrent();
        $this->view->groups       = $_SESSION['groups'] ?? Group::getCatalog();
        $this->view->menu         = $_SESSION['menu'] ?? Page::getMenuTree(true);
        $this->view->cart_count   = OrderItem::getCount();
        $this->view->current_page = intval(Request::get('page') ?? 1);

        $this->view->districts    = District::getList(true);
        $this->view->regions      = Region::getList(true);
        $this->view->cities       = City::getList(true);
    }

    /**
     * Проверяет доступ и формирует полное имя action
     * @param string $action
     * @param null $param
     * @throws NotFoundException
     */
    public function action(string $action, $param = null)
    {
        if ($this->access()) {
            if (method_exists($this, $action)) {
                if (method_exists($this, 'before')) $this->before();
                $this->$action($param ?? null);
                die;
            } else {
                $exc = new NotFoundException('Не найдено!');
                Logger::getInstance()->error($exc);
                throw $exc;
            }
        } else {
            header('HTTP/1.1 403 Forbidden', 403);
            die('Доступ запрещен!');
        }
    }

    /**
     * Проверяет доступ
     * @return bool
     */
    protected function access():bool
    {
        return true;
    }
}
