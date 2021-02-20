<?php

namespace Exceptions;

/**
 * Class NotFoundException
 * @package App\Exceptions
 */
class NotFoundException extends BaseException
{
    protected $code = 404;
    protected $error = 'Страница не найдена';
    protected $message = 'Неправильно набран адрес или такой страницы не существует';
}
