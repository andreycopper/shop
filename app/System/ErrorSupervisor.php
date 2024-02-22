<?php
namespace System;

use Throwable;
use Exceptions\SystemException;
use System\Loggers\ErrorLogger;
use System\Loggers\WarningLogger;

class ErrorSupervisor
{
    public function __construct()
    {
        set_error_handler([$this, 'OtherErrorCatcher']); // регистрация ошибок
        register_shutdown_function([$this, 'FatalErrorCatcher']); // перехват критических ошибок
        set_exception_handler([$this, 'ExceptionCatcher']); // перехват исключений
        ob_start(); // создание буфера вывода
    }

    /**
     * Обрабатывает не критические ошибки
     * @param $errno - код
     * @param $errstr - сообщение
     * @param null $errfile - файл
     * @param null $errline - строка
     */
    public function OtherErrorCatcher($errno, $errstr, $errfile = null, $errline = null)
    {
        WarningLogger::getInstance()->warning("Lvl {$errno}. {$errstr}\n{$errfile}:{$errline}");
    }

    /**
     * Обрабатывает критические ошибки
     * @throws SystemException
     */
    public function FatalErrorCatcher()
    {
        if (!empty($error) && in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR, E_CORE_ERROR])) {
            ob_end_clean();    // сбросить буфер, завершить работу буфера
            throw new SystemException($error['message']);
        } else ob_end_flush();	// вывод буфера, завершить работу буфера
    }

    /**
     * Обрабатывает неперхваченные исключения
     * @param \Throwable $e
     */
    public function ExceptionCatcher(Throwable $e)
    {
        ErrorLogger::getInstance()->error("Code {$e->getCode()}. {$e->getMessage()}\n{$e->getFile()}:{$e->getLine()}");
        echo $e->getMessage();
        die;
    }
}

new ErrorSupervisor();
