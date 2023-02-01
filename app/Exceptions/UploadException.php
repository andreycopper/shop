<?php

namespace Exceptions;

use Throwable;
use System\Logger;

/**
 * Class UploaderException
 * @package App\Exceptions
 */
class UploadException extends BaseException
{
    protected $code = 400;
    protected $error = 'Некорректный запрос';
    protected $message = 'Не удалось загрузить файл';
}
