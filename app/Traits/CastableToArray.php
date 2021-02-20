<?php

namespace Traits;

trait CastableToArray
{
    public function toArray()
    {
        return get_object_vars($this);
    }
}
