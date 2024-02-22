<?php
namespace Utils;

class SessionCache extends Cache
{
    /**
     * Возвращает текущего пользователя из сессии
     * @return mixed|null
     */
    public static function getUser()
    {
        return !empty($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    /**
     * Возвращает меню из сессии (главное, каталог, кабинет)
     * @param $type - тип меню
     * @return array|false
     */
    public static function getMenu($type)
    {
        return
            !empty($_SESSION['menu'][$type]) && !empty($_SESSION['menu'][$type]['menu']) &&
            !empty($_SESSION['menu'][$type]['expire']) && $_SESSION['menu'][$type]['expire'] > time() ?
                $_SESSION['menu'][$type]['menu'] :
                null;
    }

    public static function saveMenu($type, $data)
    {
        if (!empty($data)) {
            $_SESSION['menu'][$type]['expire'] = (time() + (60 * 60 * 24));
            $_SESSION['menu'][$type]['menu'] = $data;
        }
    }

    public static function getSettings()
    {
        return
            !empty($_SESSION['settings']) && !empty($_SESSION['settings']['settings']) &&
            !empty($_SESSION['settings']['expire']) && $_SESSION['settings']['expire'] > time() ?
                $_SESSION['settings']['settings'] :
                null;
    }

    public static function saveSettings($data)
    {
        if (!empty($data)) {
            $_SESSION['settings']['expire'] = (time() + (60 * 60 * 24));
            $_SESSION['settings']['settings'] = $data;
        }
    }

    public static function getCategory($name)
    {
        return
            !empty($_SESSION['categories'][$name]) && !empty($_SESSION['categories'][$name]['category']) &&
            !empty($_SESSION['categories'][$name]['expire']) && $_SESSION['categories'][$name]['expire'] > time() ?
                $_SESSION['categories'][$name]['category'] :
                null;
    }

    public static function saveCategory($name, $data)
    {
        if (!empty($data)) {
            $_SESSION['categories'][$name]['expire'] = (time() + (60 * 60 * 24));
            $_SESSION['categories'][$name]['category'] = $data;
        }
    }
}
