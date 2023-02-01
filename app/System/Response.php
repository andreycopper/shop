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
    public static function result(bool $result, string $message = '', array $data = [])
    {
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
