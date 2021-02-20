<?php

namespace Traits;

/*
 * trait ArrayAccess
 * Реализация ArrayAccess
 * Делает возможным обращение к объекту как к массиву
 *
 * @package App\Traits
 */
trait ArrayAccess
{
    //protected $data = [];

    public function offsetExists($offset)
    {
        return isset($this[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this[$offset];
    }

    public function offsetSet($offset, $value)
    {
        return $this[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this[$offset]);
    }
}