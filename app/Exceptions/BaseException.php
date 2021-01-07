<?php

namespace App\Exceptions;

/**
 * Class BaseException
 * @package App\Exceptions
 */
class BaseException extends \Exception
{
    protected $error = '';

    public function getError()
    {
        return $this->error;
    }
}
