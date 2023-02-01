<?php

namespace Exceptions;

use Throwable;
use System\Logger;

/**
 * Class MailException
 * @package App\Exceptions
 */
class MailException extends BaseException
{
    protected $code = 400;
    protected $error = 'Некорректный запрос';
    protected $message = 'Не удалось отправить сообщение';
}
