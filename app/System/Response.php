<?php

namespace System;

class Response
{
    /**
     * Возвращает ответ пользователю
     * @param bool $result - результат работы
     * @param string $message - сообщение
     * @param array $data - данные
     * @return bool|void
     */
    public static function result(int $code = 200, bool $result = true, string $message = '', array $data = [])
    {
        $status = match ($code) {
            200 => 'HTTP/1.1 200 OK',
            400 => 'HTTP/1.1 400 Bad Request',
            401 => 'HTTP/1.1 401 Unauthorized',
            403 => 'HTTP/1.1 403 Forbidden',
            404 => 'HTTP/1.1 404 Not Found',
            default => 'HTTP/1.1 500 Internal Server Error',
        };

        http_response_code($code);
        header($status);

        if (Request::isAjax()) {
            echo json_encode([
                'result' => $result,
                'message' => $message,
                'data' => $data,
            ]);
            die;
        }
        else return $result;
    }
}
