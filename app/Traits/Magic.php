<?php

namespace Traits;

/**
 * Реализует магические методы __set(), __get() и __isset()
 * Trait Magic
 * @package App\Traits
 */
trait Magic
{
    //protected $data = [];

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }
}