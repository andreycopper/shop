<?php

namespace Traits;

/*
 * trait Count
 * Реализует интерфейс Countable
 *
 * @package App\Traits
 * @return int
 */
trait Count
{
    //protected $data = [];

    public function count(): int
    {
        return count($this);
    }
}