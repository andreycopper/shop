<?php
namespace System;

use Models\Page;
use ReflectionClass;
use Controllers\Index;
use Controllers\Catalog;
use ReflectionException;
use Models\Product\Product;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

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

        foreach ($parts as $part) {
            $elem = ucfirst(str_replace('-', '_', $part));

            if (!empty($elem)) $routes[] = $elem;
        }

        define('ROUTE', array_values($routes)); // ['Catalog', 'Conditioners', 'Mobile', '335']
    }

    /**
     * Формируется адрес контроллера и его экшн по типу \Controller\Catalog -> actionDefault
     * Сначала проверяется путь \Controller\Blog\News\10 -> actionShow(10)
     * Затем проверяется путь \Controller\Blog\News\Edit\10 -> actionEdit(10)
     * Затем проверяется путь \Controller\Blog\News -> actionDefault()
     * Проверка идет с конца адресной строки
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public static function start()
    {
        if (!empty(ROUTE[0]) && in_array(ROUTE[0], ['Js', 'Css'])) return;

        $routes  = ROUTE;
        $count  = count($routes);

        if (!empty($routes[0]) && $routes[0] === 'Catalog' && class_exists('Controllers\\Catalog')) {
            unset($routes[0]);
            self::startCatalogRoute(array_values($routes));
        }
        elseif ($count === 0) self::run('Controllers\\Index', 'actionDefault');
        elseif ($count === 1) self::startSingleRoute($routes);
        elseif ($count === 2) self::startDoubleRoute($routes);
        elseif ($count === 3) self::startTrippleRoute($routes);
        else self::startFourRoute($routes);
    }

    /**
     * @param $routes - массив роутов
     * @throws NotFoundException
     */
    private static function startSingleRoute($routes)
    {
        if (class_exists("Controllers\\{$routes[0]}") && method_exists("Controllers\\{$routes[0]}", 'actionDefault')) { // Controllers\Some -> actionDefault()
            $class = "Controllers\\{$routes[0]}";
            $method = 'actionDefault';
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\Index") && method_exists("Controllers\\{$routes[0]}\\Index", 'actionDefault')) { // Controllers\Some\Index -> actionDefault()
            $class  = "Controllers\\{$routes[0]}\\Index";
            $method = 'actionDefault';
        }
        elseif (class_exists('Controllers\\Index') && method_exists('Controllers\\Index', "action{$routes[0]}")) { // Controllers\Index -> actionSome()
            $class  = 'Controllers\\Index';
            $method = "action{$routes[0]}";
        }
        else throw new NotFoundException();

        self::run($class, $method);
    }

    /**
     * @param $routes - массив роутов
     * @throws NotFoundException
     * @throws ReflectionException
     */
    private static function startDoubleRoute($routes)
    {
        if (class_exists("Controllers\\{$routes[0]}") && method_exists("Controllers\\{$routes[0]}", "action{$routes[1]}")) { // Controllers\Some -> actionAnother()
            $class  = "Controllers\\{$routes[0]}";
            $method = "action{$routes[1]}";
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\Index") && method_exists("Controllers\\{$routes[0]}\\Index", "action{$routes[1]}")) { // Controllers\Some\Index -> actionAnother()
            $class  = "Controllers\\{$routes[0]}\\Index";
            $method = "action{$routes[1]}";
        }
        elseif (class_exists("Controllers\\{$routes[0]}") && method_exists("Controllers\\{$routes[0]}", 'actionShow')) { // Controllers\Some -> actionShow(10)
            $class  = "Controllers\\{$routes[0]}";
            $method = 'actionShow';
            $params = [0 => mb_strtolower($routes[1])];
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\Index") && method_exists("Controllers\\{$routes[0]}\\Index", 'actionShow')) { // Controllers\Some\Index -> actionShow(10)
            $class  = "Controllers\\{$routes[0]}\\Index";
            $method = 'actionShow';
            $params = [0 => mb_strtolower($routes[1])];
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}", 'actionDefault')) { // Controllers\Some\Another -> actionDefault()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}";
            $method = 'actionDefault';
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\Index") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\Index", 'actionDefault')) { // Controllers\Some\Another\Index -> actionDefault()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\Index";
            $method = 'actionDefault';
        }
        elseif (class_exists("Controllers\\Index") && method_exists("Controllers\\Index", "action{$routes[0]}")) { // Controllers\Index -> actionSome('param')
            if ((new ReflectionClass(Index::class))->getMethod("action{$routes[0]}")->getNumberOfParameters() === 1) {
                $class  = "Controllers\\Index";
                $method = "action{$routes[0]}";
                $params = [0 => mb_strtolower($routes[1])];
            }
            else throw new NotFoundException();
        }
        else throw new NotFoundException();

        self::run($class, $method, $params ?? []);
    }

    /**
     * @param $routes - массив роутов
     * @throws NotFoundException
     * @throws ReflectionException
     */
    private static function startTrippleRoute($routes)
    {
        if (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}", "action{$routes[2]}")) { // Controllers\Some\Another -> actionMethod()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}";
            $method = "action{$routes[2]}";
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}", 'actionShow')) { // Controllers\Some\Another -> actionShow(10)
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}";
            $method = 'actionShow';
            $params = [0 => mb_strtolower($routes[2])];
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\Index") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\Index", "action{$routes[2]}")) { // Controllers\Some\Another\Index -> actionMethod()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\Index";
            $method = "action{$routes[2]}";
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\Index") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\Index", 'actionShow')) { // Controllers\Some\Another\Index -> actionShow(10)
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\Index";
            $method = 'actionShow';
            $params = [0 => mb_strtolower($routes[2])];
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}", 'actionDefault')) { // Controllers\Some\Another\Else -> actionDefault()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}";
            $method = 'actionDefault';
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\Index") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\Index", 'actionDefault')) { // Controllers\Some\Another\Else\Index -> actionDefault()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\Index";
            $method = 'actionDefault';
        }
        elseif (class_exists("Controllers\\{$routes[0]}") && method_exists("Controllers\\{$routes[0]}", "action{$routes[1]}")) { // Controllers\Some -> actionAnother('param')
            $class = "Controllers\\{$routes[0]}";
            if ((new ReflectionClass(new $class))->getMethod("action{$routes[1]}")->getNumberOfParameters() === 1) {
                $method = "action{$routes[1]}";
                $params = [0 => mb_strtolower($routes[2])];
            }
            else throw new NotFoundException();
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\Index") && method_exists("Controllers\\{$routes[0]}\\Index", "action{$routes[1]}")) { // Controllers\Some\Index -> actionAnother('param')
            $class = "Controllers\\{$routes[0]}\\Index";
            if ((new ReflectionClass(new $class))->getMethod("action{$routes[1]}")->getNumberOfParameters() === 1) {
                $method = "action{$routes[1]}";
                $params = [0 => mb_strtolower($routes[2])];
            }
            else throw new NotFoundException();
        }
        elseif (class_exists('Controllers\\Index') && method_exists('Controllers\\Index', "action{$routes[0]}")) { // Controllers\Index -> actionSome('param1', 'param2')
            if ((new ReflectionClass(Index::class))->getMethod("action{$routes[0]}")->getNumberOfParameters() === 2) {
                $class = 'Controllers\\Index';
                $method = "action{$routes[0]}";
                $params = [0 => mb_strtolower($routes[1]), 1 => mb_strtolower($routes[2])];
            }
            else throw new NotFoundException();
        }
        else throw new NotFoundException();

        self::run($class, $method, $params ?? []);
    }

    /**
     * @param $routes - массив роутов
     * @throws NotFoundException
     * @throws ReflectionException
     */
    private static function startFourRoute($routes)
    {
        if (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}", "action{$routes[3]}")) { // Controllers\Some\Another\Else -> actionMethod()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}";
            $method = "action{$routes[3]}";
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}", 'actionShow')) { // Controllers\Some\Another\Else -> actionShow(10)
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}";
            $method = 'actionShow';
            $params = [0 => mb_strtolower($routes[3])];
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\Index") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\Index", "action{$routes[3]}")) { // Controllers\Some\Another\Else\Index -> actionMethod()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\Index";
            $method = "action{$routes[3]}";
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\Index") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\Index", 'actionShow')) { // Controllers\Some\Another\Else\Index -> actionShow(10)
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\Index";
            $method = 'actionShow';
            $params = [0 => mb_strtolower($routes[3])];
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\{$routes[3]}") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\{$routes[3]}", 'actionDefault')) { // Controllers\Some\Another\Else\One -> actionDefault()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\{$routes[3]}";
            $method = 'actionDefault';
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\{$routes[3]}\\Index") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\{$routes[3]}\\Index", 'actionDefault')) { // Controllers\Some\Another\Else\One\Index -> actionDefault()
            $class  = "Controllers\\{$routes[0]}\\{$routes[1]}\\{$routes[2]}\\{$routes[3]}\\Index";
            $method = 'actionDefault';
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}", "action{$routes[2]}")) { // Controllers\Some\Another -> actionMethod('param')
            $class = "Controllers\\{$routes[0]}\\{$routes[1]}";
            if ((new ReflectionClass(new $class))->getMethod("action{$routes[2]}")->getNumberOfParameters() === 1) {
                $method = "action{$routes[2]}";
                $params = [0 => mb_strtolower($routes[3])];
            }
            else throw new NotFoundException();
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\Index") && method_exists("Controllers\\{$routes[0]}\\{$routes[1]}\\Index", "action{$routes[2]}")) { // Controllers\Some\Another\Index -> actionMethod('param')
            $class = "Controllers\\{$routes[0]}\\{$routes[1]}\\Index";
            if ((new ReflectionClass(new $class))->getMethod("action{$routes[2]}")->getNumberOfParameters() === 1) {
                $method = "action{$routes[2]}";
                $params = [0 => mb_strtolower($routes[3])];
            }
            else throw new NotFoundException();
        }
        elseif (class_exists("Controllers\\{$routes[0]}") && method_exists("Controllers\\{$routes[0]}", "action{$routes[1]}")) { // Controllers\Some -> actionMethod('param1', 'param2')
            $class = "Controllers\\{$routes[0]}";
            if ((new ReflectionClass(new $class))->getMethod("action{$routes[1]}")->getNumberOfParameters() === 2) {
                $method = "action{$routes[1]}";
                $params = [0 => mb_strtolower($routes[2]), 1 => mb_strtolower($routes[3])];
            }
            else throw new NotFoundException();
        }
        elseif (class_exists("Controllers\\{$routes[0]}\\Index") && method_exists("Controllers\\{$routes[0]}\\Index", "action{$routes[1]}")) { // Controllers\Some -> actionMethod('param1', 'param2')
            $class = "Controllers\\{$routes[0]}\\Index";
            if ((new ReflectionClass(new $class))->getMethod("action{$routes[1]}")->getNumberOfParameters() === 2) {
                $method = "action{$routes[1]}";
                $params = [0 => mb_strtolower($routes[2]), 1 => mb_strtolower($routes[3])];
            }
            else throw new NotFoundException();
        }
        elseif (class_exists('Controllers\\Index') && method_exists('Controllers\\Index', "action{$routes[0]}")) { // Controllers\Index -> actionSome('param1', 'param2', 'param3')
            if ((new ReflectionClass(Index::class))->getMethod("action{$routes[0]}")->getNumberOfParameters() === 3) {
                $class = 'Controllers\\Index';
                $method = "action{$routes[0]}";
                $params = [0 => mb_strtolower($routes[1]), 1 => mb_strtolower($routes[2]), 2 => mb_strtolower($routes[3])];
            }
            else throw new NotFoundException();
        }
        else throw new NotFoundException();

        self::run($class, $method, $params ?? []);
    }

    /**
     * @param $routes - массив роутов
     * @throws ReflectionException
     */
    private static function startCatalogRoute($routes)
    {
        $class = 'Controllers\\Catalog';
        $countRoutes = count($routes);
        $reflection = new ReflectionClass(Catalog::class);

        if ($countRoutes === 4) {
            if (method_exists($class, "action{$routes[0]}") && $reflection->getMethod("action{$routes[0]}")->getNumberOfParameters() === 3) { // Controllers\Catalog -> actionSome('mobile', 10, 2)
                $method = "action{$routes[0]}";
                $params = [0 => mb_strtolower($routes[1]), 1 => mb_strtolower($routes[2]), 2 => mb_strtolower($routes[3])];
            }
            else { // Controllers\Catalog -> actionShow('mobile'|10)
                $method = "actionShow";
                $params = [0 => mb_strtolower($routes[3])];
            }
        }
        elseif ($countRoutes === 3) {
            if (method_exists($class, "action{$routes[0]}") && $reflection->getMethod("action{$routes[0]}")->getNumberOfParameters() === 2) { // Controllers\Catalog -> actionSome('mobile', 10)
                $method = "action{$routes[0]}";
                $params = [0 => mb_strtolower($routes[1]), 1 => mb_strtolower($routes[2])];
            }
            else { // Controllers\Catalog -> actionShow('mobile'|10)
                $method = "actionShow";
                $params = [0 => mb_strtolower($routes[2])];
            }
        }
        elseif ($countRoutes === 2) {
            if (method_exists($class, "action{$routes[0]}") && $reflection->getMethod("action{$routes[0]}")->getNumberOfParameters() === 1) { // Controllers\Catalog -> actionSome(10)
                $method = "action{$routes[0]}";
                $params = [0 => mb_strtolower($routes[1])];
            }
            else { // Controllers\Catalog -> actionShow('mobile'|10)
                $method = "actionShow";
                $params = [0 => mb_strtolower($routes[1])];
            }
        }
        elseif ($countRoutes === 1) {
            if (method_exists($class, "action{$routes[0]}") && $reflection->getMethod("action{$routes[0]}")->getNumberOfParameters() === 0) { // Controllers\Catalog -> actionSome()
                $method = "action{$routes[0]}";
            }
            else { // Controllers\Catalog -> actionShow('mobile'|10)
                $method = "actionShow";
                $params = [0 => mb_strtolower($routes[0])];
            }
        }
        else $method = 'actionDefault';

        self::run($class, $method, $params ?? []);
    }

    /**
     * Старт контроллера
     * @param $class - класс контроллера
     * @param $method - метод контроллера
     * @param array $params - параметры метода
     */
    public static function run($class, $method, $params = [])
    {
        if (!empty($class) && !empty($method)) {
            $controller = new $class;

            if (isset($params[2])) $controller->action($method, mb_strtolower($params[0]), mb_strtolower($params[1]), mb_strtolower($params[2]));
            if (isset($params[1])) $controller->action($method, mb_strtolower($params[0]), mb_strtolower($params[1]));
            elseif (isset($params[0])) $controller->action($method, mb_strtolower($params[0]));
            else $controller->action($method);
        }

        die;
    }
}
