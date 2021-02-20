<?php

namespace Exceptions;

/**
 * Class ForbiddenException
 * @package App\Exceptions
 */
class ForbiddenException extends BaseException
{
    protected $code = 403;
    protected $error = 'Доступ запрещен';
    protected $message = 'У вас нет доступа к данному разделу';
}
