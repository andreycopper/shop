<?php
namespace Exceptions;

use Throwable;
use System\Loggers\AccessLogger;

/**
 * Class UserException
 * @package App\Exceptions
 */
class UserException extends BaseException
{
    public function __construct($message = 'Auth error', $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        AccessLogger::getInstance()->error($this);
    }
}
