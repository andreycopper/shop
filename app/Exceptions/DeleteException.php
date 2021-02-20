<?php

namespace Exceptions;

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
