<?php
namespace Models;

use Utils\Cache;

class Setting extends Model
{
    protected static $db_table = 'shop.settings';

    /**
     * Возвращает массив настроек из кэша, БД
     * @return array
     */
    public static function getSiteSettings()
    {
        $settings = Cache::getSettings();
        if (!empty($settings)) return $settings;

        $data = Setting::getList();
        $settings = [];
        if (!empty($data) && is_array($data)) {
            foreach ($data as $item) {
                $settings[$item['name']] = $item['value'];
            }

            Cache::saveSettings($settings);
        }

        return $settings;
    }
}
