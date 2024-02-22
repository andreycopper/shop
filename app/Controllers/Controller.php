<?php
namespace Controllers;

use Entity\User;
use Models\Model;
use System\Crypt;
use Views\View;
use Models\Page;
use System\Logger;
use System\Request;
use Models\User\User as ModelUser;
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
    protected View $view;
    protected User $user;
    protected ?Model $model = null;
    protected int $currentPage;
    protected int $elementsPerPage = 10;

    //protected ?Crypt $crypt; // объект шифрования
    //protected string $csrf;
    //protected $publicKey;       // публичный ключ шифрования

    public function __construct()
    {
        $this->view = new View();

        $this->user = User::getCurrent();
        $this->currentPage = intval(Request::get('page') ?? 1);


        $this->set('user', $this->user);
        $this->set('currentPage', $this->currentPage);
        $this->set('elementsPerPage', $this->elementsPerPage);

        $this->set('menuMain', Page::getMenu('main'));
        $this->set('menuPersonal', Page::getMenu('personal'));
        $this->set('menuCatalog', Page::getMenu('catalog'));

        $this->set('cartCount', OrderItem::getCount($this->user));

//        $this->set('location', $_SESSION['location'] ?? User::getLocation()); // текущее местоположение
//        $this->set('districts', District::get()); // список федеральных округов
//        $this->set('page', Page::getPageInfo(URL)); // информация о странице
//        $this->set('breadcrumbs', URL); // breadcrumbs
    }

    /**
     * Проверяет доступ и формирует полное имя action
     * @param string $action
     * @param null $param1
     * @param null $param2
     * @param null $param3
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function action(string $action, $param1 = null, $param2 = null, $param3 = null)
    {
        if (method_exists($this, $action)) {
            if ($this->access($action)) {
                if (method_exists($this, 'before')) $this->before();

                if (!is_null($param1) && !is_null($param2) && !is_null($param3)) $this->$action($param1, $param2, $param3);
                if (!is_null($param1) && !is_null($param2)) $this->$action($param1, $param2);
                elseif (!is_null($param1)) $this->$action($param1);
                else $this->$action();

                if (method_exists($this, 'after')) $this->after();
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
     * Устанавливает директорию шаблона
     * @param $template - имя директории шаблона
     */
    protected function setTemplate(string $template)
    {
        $this->view->setTemplate($template);
    }

    /**
     * Возвращает отрендеренный файл
     * @param $file
     * @param array $vars
     * @return false|string|null
     */
    protected function render($file, $vars = [])
    {
        return $this->view->render($file, $vars = []);
    }

    /**
     * Отображает HTML-код шаблона
     * @param $file
     */
    protected function display($file)
    {
        $this->view->display($file);
    }

    /**
     * Отображает HTML-код файла
     * @param $file
     */
    protected function display_element($file)
    {
        $this->view->display_element($file);
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
