<?php

namespace Exceptions;

use Throwable;
use System\Access;

/**
 * Class ForbiddenException
 * @package App\Exceptions
 */
class ForbiddenException extends BaseException
{
    protected $code = 403;
    protected $error = 'Доступ запрещен';
    protected $message = 'У вас нет доступа к данному разделу';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        Access::getInstance()->error($this);
    }
}
