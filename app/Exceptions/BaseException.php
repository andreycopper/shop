<?php

namespace App\Exceptions;

use App\System\Logger;
use Throwable;

/**
 * Class BaseException
 * @package App\Exceptions
 */
class BaseException extends \Exception
{
    protected $error = '';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        Logger::getInstance()->error($this);
    }

    public function getError()
    {
        return $this->error;
    }
}
