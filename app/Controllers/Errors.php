<?php

namespace Controllers;

use Views\View;

/**
 * Class Errors
 * @package App\Controllers
 */
class Errors extends Controller
{
    public function __construct($e)
    {
        $this->view = new View();
        $this->view->code    = $e->getCode();
        $this->view->error   = $e->getError();
        $this->view->message = $e->getMessage();
    }

    /**
     * Проблема с запросом
     */
    protected function action400()
    {
        header('HTTP/1.1 400 Bad Request ', 400);
        $this->view->display('errors/error');
        die();
    }

    /**
     * Доступ запрещен
     */
    protected function action403()
    {
        header('HTTP/1.1 403 Forbidden', 403);
        $this->view->display('errors/error');
        die();
    }

    /**
     * Элемент не найден
     */
    protected function action404()
    {
        header('HTTP/1.1 404 Not Found', 404);
        $this->view->display('errors/error');
        die();
    }

    /**
     * Нет соединения с базой данных
     */
    protected function action500()
    {
        header('HTTP/1.1 500 Internal Server Error', 500);
        $this->view->display('errors/error');
        die();
    }
}
