<?php

namespace App\Models;

class UserType extends Model
{
    protected static $table = 'user_types';
    public $id;            // id
    public $active;        // активность
    public $name;          // контактное лицо
    public $created;          // контактное лицо
}
