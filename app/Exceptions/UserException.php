<?php

namespace App\Exceptions;

use App\System\Logger;
use Throwable;

/**
 * Class UserException
 * @package App\Exceptions
 */
class UserException extends BaseException
{
    protected $code = 400;
    protected $error = 'Некорректный запрос';
    protected $message = 'Ошибка авторизации';
}
