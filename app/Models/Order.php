<?php

namespace App\Models;

class Order extends Model
{
    protected static $table = 'orders';
    public $id;          // id
    public $active;      // активность
    public $name;        // название
    public $description; // описание
    public $price;       // стоимость
    public $price_from;  // стоимость от
    public $price_to;    // стоимость до
    public $time;        // срок
    public $created;     // дата создания
}
