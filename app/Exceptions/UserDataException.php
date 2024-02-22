<?php
namespace Exceptions;

use System\Loggers\WarningLogger;
use Throwable;
use System\Loggers\AccessLogger;

/**
 * Class UserDataException
 * @package App\Exceptions
 */
class UserDataException extends BaseException
{
    public function __construct($message = 'Incorrect data', $code = 406, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        WarningLogger::getInstance()->error($this);
    }
}
