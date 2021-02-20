<?php

namespace Models;

use System\Db;

class Test extends Model
{
    protected static $table = 'test';

    public static function fias($object = null)
    {
        $sql = "SELECT id, shortname FROM fias_shortnames";
        $db = new Db();
        $data = $db->query($sql, []);
    }
}
