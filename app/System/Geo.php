<?php

namespace App\System;

class Geo
{
    /**
     * Получает ip-адрес пользователя
     * @return false|mixed
     */
    public static function GetUserIP()
    {
        $ip_pattern = "/(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/";
        $search_keys = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
        ];
        $local_ip_pattern = "/^(10|127|172|192\\.168)\\./";
        $ips = [];

        foreach ($search_keys as $k) {
            if (isset($_SERVER[$k]) && preg_match($ip_pattern, $_SERVER[$k], $_v)) {
                foreach ($_v as $__v) {
                    $ips[] = $__v;
                }
            }
        }

        do {
            $_v = array_shift($ips);
            if (!preg_match($local_ip_pattern, $_v)) $ip = $_v;
        } while (empty($ip) && count($ips) > 0);

        return $ip ?? false;
    }

    /**
     * Получает местоположение пользоваетеля (страна, регион, город, координаты)
     * @param $ip
     * @return array|false
     */
    public static function GetLocationFromIP($ip)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://ipgeobase.ru:7020/geo?ip=$ip");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, md5(rand()));

        $xml = curl_exec($ch);
        if (empty($xml)) return false;

        preg_match('/encoding="(.*)"/', $xml, $coding);
        if (!preg_match('/<country>(.*)<\/country>/', $xml, $country)) return false;
        if (!preg_match('/<region>(.*)<\/region>/', $xml, $region)) return false;
        if (!preg_match('/<city>(.*)<\/city>/', $xml, $city)) return false;
        if (!preg_match('/<lat>(.*)<\/lat>/', $xml, $lat)) return false;
        if (!preg_match('/<lng>(.*)<\/lng>/', $xml, $lng)) return false;

        return [
            'country' => $country[1],
            'region' => iconv($coding ? $coding[1] : 'windows-1251', "utf-8", $region[1]),
            'city' => iconv($coding ? $coding[1] : 'windows-1251', "utf-8", $city[1]),
            'lat' => round($lat[1], 4),
            'lng' => round($lng[1], 4)
        ];
    }
}
