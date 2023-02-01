<?php

namespace System;

use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;
use Models\Group;
use Models\Page;
use Models\Product\Product;

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
        $uri = explode('?', $uri)[0];
        $uri = mb_substr(trim($uri), 1, mb_strlen($uri) - 2);
        $parts  = explode('/', $uri);
        $routes = [];
        $urls = [];
        $link = '';

        foreach ($parts as $part) {
            $elem = ucfirst(str_replace('-', '_', $part));
            $link .= ($link ? '/' : '') . str_replace('-', '_', $part);

            if (!empty($elem)) {
                $routes[] = $elem;

                $urls[] = [
                    'name' => $elem,
                    'link' => $link,
                    'title' =>
                        (!empty($parts[0]) && $parts[0] === 'catalog' && $part !== 'catalog') ?
                            is_numeric($part) ?
                                Product::getName('id', intval($elem)) :
                                Group::getName('link', mb_strtolower($elem)) :
                            Page::getName('link', mb_strtolower($elem))
                ];
            }
        }

        define('ROUTE', $routes); // ['Catalog', 'Conditioners', 'Mobile', '335']
        define('URL', $urls); // [['name' => 'Personal', 'link' => 'personal'], ['name' => 'Subscriptions', 'link' => 'personal/subscriptions']]
    }

    /**
     * Формируется адрес контроллера и его экшн по типу \App\Controller\Catalog -> actionDefault
     * Сначала проверяется путь App\Controller\Blog\News\10 -> actionShow(10)
     * Затем проверяется путь App\Controller\Blog\News\Edit\10 -> actionEdit(10)
     * Затем проверяется путь App\Controller\Blog\News -> actionDefault()
     * Проверка идет с конца адресной строки
     * @throws NotFoundException|ForbiddenException
     */
    public static function start()
    {
        self::parseUrl($_SERVER['REQUEST_URI']);

        $class  = null;            // класс контроллера
        $action = null;            // метод контроллера
        $param  = null;          // параметр метода
        $route  = ROUTE;         // массив роутов
        $count  = count($route); // количество роутов

        /* new */
        if ($count > 1) {
            $last = $count - 1; // индекс последнего элемента массива роутов

            if ($route[0] === 'Catalog') { //catalog/conditioners/mobile/10/
                if (class_exists('Controllers\\Catalog')) {
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
            }
            else { //shop/blog/news/10/
                $base = 'Controllers';
                for ($i = 0; $i < $last - 1; $i++) {
                    $base .= '\\' . $route[$i];
                }

                if (class_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last] . '\\Index') &&
                    method_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last] . '\\Index', 'actionDefault'))
                { // \Controllers\Blog\Index -> actionDefault()
                    $class = $base . '\\' . $route[$last - 1] . '\\' . $route[$last] . '\\Index';
                    $action = 'actionDefault';
                }
                elseif (class_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last]) &&
                    method_exists($base . '\\' . $route[$last - 1] . '\\' . $route[$last], 'actionDefault'))
                { // Controllers\Blog\News -> actionDefault()
                    $class = $base . '\\' . $route[$last - 1] . '\\' . $route[$last];
                    $action = 'actionDefault';
                }
                elseif (class_exists($base . '\\' . $route[$last - 1])) {
                    if (method_exists($base . '\\' . $route[$last - 1], 'action' . $route[$last])) { // Controllers\Blog\News -> actionSave()
                        $class = $base . '\\' . $route[$last - 1];
                        $action = 'action' . $route[$last];
                    }
                    elseif (method_exists($base . '\\' . $route[$last - 1], 'actionShow')) { // Controllers\Blog\News -> actionShow(10)
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
        elseif ($count === 1) { //shop/blog/
            if (class_exists('Controllers\\' . $route[0]) &&
                method_exists('Controllers\\' . $route[0], 'actionDefault'))
            { // Controllers\Blog -> actionDefault()
                $class  = 'Controllers\\' . $route[0];
                $action = 'actionDefault';
            }
            elseif (class_exists('Controllers\\' . $route[0] . '\\Index') &&
                method_exists('Controllers\\' . $route[0] . '\\Index', 'actionDefault'))
            { // Controllers\Blog\Index -> actionDefault()
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
            $controller->action($action, mb_strtolower($param) ?? null);
        }
        else throw new NotFoundException();
    }
}
