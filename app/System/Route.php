<?php

namespace System;

use Exceptions\DbException;
use Exceptions\DeleteException;
use Exceptions\EditException;
use Exceptions\ForbiddenException;
use Exceptions\MailException;
use Exceptions\NotFoundException;
use Exceptions\UploadException;
use Exceptions\UserException;

/**
 * Class Route
 * @package App\System
 */
class Route
{
    /**
     * Разбираем url на составные части и объявляем константу с ними
     * @param $uri
     */
    public static function parseUrl($uri)
    {
        $uri    = mb_substr(trim($uri), 1);
        $parts  = explode('/', $uri);
        $routes = [];

        foreach ($parts as $part) {
            $elem = ucfirst(str_replace('-', '_', $part));

            if (!empty($elem) && mb_substr($part, 0, 1) !== '?') {
                $routes[] = $elem;
            } else {
                continue;
            }
        }

        define('ROUTE', $routes);
    }

    /**
     * Формируется адрес контроллера и его экшн по типу \App\Controller\Catalog -> actionDefault
     * Сначала проверяется путь App\Controller\Blog\News\10 -> actionShow(10)
     * Затем проверяется путь App\Controller\Blog\News\Edit\10 -> actionEdit(10)
     * Затем проверяется путь App\Controller\Blog\News -> actionDefault()
     * Проверка идет с конца адресной строки
     * @throws NotFoundException
     */
    public static function start()
    {
        $class  = '';            // класс контроллера
        $action = '';            // метод контроллера
        $route  = ROUTE;         // массив роутов
        $count  = count($route); // количество роутов

        /* new */
        if ($count > 1) {
            $last = $count - 1; // индекс последнего элемента массива роутов

            if ($route[0] === 'Catalog') {

                if (method_exists('Controllers\\Catalog', 'action' . $route[$last])) { // Controllers\Catalog -> actionSave()
                    $class = 'Controllers\\Catalog';
                    $action = 'action' . $route[$last];
                }
                elseif (method_exists('Controllers\\Catalog', 'action' . $route[$last - 1])) { // Controllers\Catalog -> actionEdit('10')
                    $class = 'Controllers\\Catalog';
                    $action = 'action' . $route[$last - 1];
                    $param = $route[$last];
                }
                else { // App\Controllers\Catalog -> actionShow('10')
                    $class = 'Controllers\\Catalog';
                    $action = 'actionShow';
                    $param = $route[$last];
                }
            }
            else {
                $base = 'Controllers';
                for ($i = 0; $i < $last - 1; $i++) {
                    $base .= '\\' . $route[$i];
                }

                if (class_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last] . '\\Index')) { // \Controllers\Blog\Index -> actionDefault()
                    $class = $base . '\\' . $route[$last - 1] . '\\' . $route[$last] . '\\Index';
                    $action = 'actionDefault';
                }
                elseif (class_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last])) { // Controllers\Blog\News -> actionDefault()
                    $class = $base . '\\' . $route[$last - 1] . '\\' . $route[$last];
                    $action = 'actionDefault';
                }
                elseif (class_exists($base . '\\' . $route[$last - 1])) {
                    if (method_exists($base . '\\' . $route[$last - 1], 'action' . $route[$last])) { // Controllers\Blog\News -> actionSave()
                        $class = $base . '\\' . $route[$last - 1];
                        $action = 'action' . $route[$last];
                    }
                    else { // Controllers\Blog\News -> actionShow(10)
                        $class = $base . '\\' . $route[$last - 1];
                        $action = 'actionShow';
                        $param = $route[$last];
                    }
                }
                elseif (class_exists($base) && method_exists($base, 'action' . $route[$last - 1])) { // Controllers\Blog -> actionEdit(10)
                    $class = $base;
                    $action = 'action' . $route[$last - 1];
                    $param = $route[$last];
                }
            }
        }
        elseif ($count === 1) {
            if (class_exists('Controllers\\' . $route[0])) { // Controllers\Blog -> actionDefault()
                $class  = 'Controllers\\' . $route[0];
                $action = 'actionDefault';
            }
            elseif (class_exists('Controllers\\' . $route[0] . '\\Index')) { // Controllers\Blog\Index -> actionDefault()
                $class  = 'Controllers\\' . $route[0] . '\\Index';
                $action = 'actionDefault';
            }
        }
        else {
            $class  = 'Controllers\\Index';
            $action = 'actionDefault';
        }

        if (!empty($class) && !empty($action)) {
            $controller = new $class;
            $controller->action($action, $param ?? null);
        } else {
            $exc = new NotFoundException();
            Logger::getInstance()->error($exc);
            throw $exc;
        }
    }
}
