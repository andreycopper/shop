<?php

namespace Controllers;

use System\Request;
use System\Response;
use Views\View;

/**
 * Class Errors
 * @package App\Controllers
 */
class Errors extends Controller
{
    private int $code;
    private string $message;

    public function __construct($e)
    {
        parent::__construct();
        $this->code = $e->getCode();
        $this->message = $e->getMessage();

        $this->set('code', $this->code);
        $this->set('message', $this->message);
    }

    /**
     * Показ страницы ошибки
     */
    protected function actionError()
    {
        if (Request::isAjax()) Response::result($this->code, false, $this->message);

        $status = match ($this->code) {
            200 => 'HTTP/1.1 200 OK',
            400 => 'HTTP/1.1 400 Bad Request',
            401 => 'HTTP/1.1 401 Unauthorized',
            403 => 'HTTP/1.1 403 Forbidden',
            404 => 'HTTP/1.1 404 Not Found',
            default => 'HTTP/1.1 500 Internal Server Error',
        };

        header($status, $this->code ?: 500);
        $this->display('errors/error');
        die();
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
