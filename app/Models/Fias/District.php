<?php

namespace Models\Fias;

use System\Cache;
use Models\Model;

class District extends Model
{
    protected static $table = 'fias.districts';

    public int $id;
    public ?bool $active;
    public string $name;
    public int $sort;
    public string $created;

    /**
     * Получает федеральные округа из кэша и БД по порядку в случае отсутствия (+)
     * @return array|bool
     */
    public static function get()
    {
        if ($data = Cache::getDistricts()) return $data;
        return self::getList();
    }
}
