<?php
namespace Utils;

class FileCache extends Cache
{
    /**
     * Возвращает меню из файла (главное, каталог, кабинет)
     * @param $type - тип меню
     * @return array|false
     */
    public static function getMenu($type)
    {
        $file = DIR_CACHE . DIRECTORY_SEPARATOR . 'menu' . DIRECTORY_SEPARATOR . $type;

        if (is_file($file) && filesize($file) > 0) {
            $data = json_decode(file_get_contents($file), true);

            if (!empty($data['menu']) && !empty($data['expire']) && $data['expire'] > time()) {
                SessionCache::saveMenu($type, $data['menu']);
                return $data['menu'];
            }
        }

        return null;
    }

    public static function saveMenu($type, $data)
    {
        $file = DIR_CACHE . DIRECTORY_SEPARATOR . 'menu' . DIRECTORY_SEPARATOR . $type;
        if (!is_dir(DIR_CACHE . DIRECTORY_SEPARATOR . 'menu')) mkdir(DIR_CACHE . DIRECTORY_SEPARATOR . 'menu');

        file_put_contents($file, json_encode([
            'expire' => (time() + (60 * 60 * 24)),
            'menu' => $data
        ], JSON_UNESCAPED_UNICODE));
    }

    public static function getSettings()
    {
        $file = DIR_CACHE . DIRECTORY_SEPARATOR . 'settings';

        if (is_file($file) && filesize($file) > 0) {
            $data = json_decode(file_get_contents($file), true);

            if (!empty($data['settings']) && !empty($data['expire']) && $data['expire'] > time()) {
                SessionCache::saveSettings($data['settings']);
                return $data['settings'];
            }
        }

        return null;
    }

    public static function saveSettings($data)
    {
        $file = DIR_CACHE . DIRECTORY_SEPARATOR . 'settings';

        file_put_contents($file, json_encode([
            'expire' => (time() + (60 * 60 * 24)),
            'settings' => $data
        ], JSON_UNESCAPED_UNICODE));
    }

    public static function getCategory($name)
    {
        $file = DIR_CACHE . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . $name;

        if (is_file($file) && filesize($file) > 0) {
            $data = json_decode(file_get_contents($file), true);

            if (!empty($data['category']) && !empty($data['expire']) && $data['expire'] > time()) {
                SessionCache::saveCategory($name, $data['category']);
                return $data['category'];
            }
        }

        return null;
    }

    public static function saveCategory($name, $data)
    {
        $file = DIR_CACHE . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR . $name;
        if (!is_dir(DIR_CACHE . DIRECTORY_SEPARATOR . 'categories')) mkdir(DIR_CACHE . DIRECTORY_SEPARATOR . 'categories');

        file_put_contents($file, json_encode([
            'expire' => (time() + (60 * 60 * 24)),
            'category' => $data
        ], JSON_UNESCAPED_UNICODE));
    }
}
