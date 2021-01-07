<?php

namespace App\Exceptions;

/**
 * Class MultiExceptions
 * @package App\Exceptions
 */
class MultiExceptions extends BaseException
{
    protected $data = [];

    public function add(\Throwable $e)
    {
        $this->data[] = $e;
    }

    public function all()
    {
        return $this->data;
    }

    public function empty()
    {
        return empty($this->data);
    }
}
