<?php

namespace App\Models;

class Payment extends Model
{
    protected static $table = 'payments';
    public $id;          // id
    public $active;      // активность
    public $name;        // название
    public $description; // описание
    public $created;     // дата создания
}
