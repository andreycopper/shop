<?php

namespace Exceptions;

use Throwable;
use System\Logger;

/**
 * Class DeleteException
 * @package App\Exceptions
 */
class DeleteException extends BaseException
{
    protected $code = 400;
    protected $error = 'Некорректный запрос';
    protected $message = 'Не удалось удалить элемент';
}
