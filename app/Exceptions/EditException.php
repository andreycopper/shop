<?php

namespace Exceptions;

use Throwable;
use System\Logger;

/**
 * Class EditException
 * @package App\Exceptions
 */
class EditException extends BaseException
{
    protected $code = 400;
    protected $error = 'Некорректный запрос';
    protected $message = 'Не удалось сохранить изменения';
}
