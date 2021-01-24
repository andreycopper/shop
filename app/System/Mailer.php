<?php

namespace App\System;

use App\Models\EventTemplate;
use App\Models\User;

/**
 * Class Mailer
 * @package App\System
 */
class Mailer
{
    const TYPE_TEXT = 'text';
    const TYPE_HTML = 'html';

    public $to;
    public $from = EMAIL;
    public $fromName = SITENAME;
    public $replyTo = EMAIL;
    protected $headers;
    public $type;
    public $subject;
    public $message;

    public function __construct(User $user, EventTemplate $template, array $params)
    {
        $this->to = $user->email;
        $this->type = $user->mailing_type;
        $this->subject = $template->subject;
        $this->message = $template->message;
        $this->prepareMessage($user, $params)->removeTags();

    }

    /**
     * Проверяет в формате ли html сообщение
     * @return bool
     */
    private function isHtml()
    {
        return self::TYPE_HTML === $this->type;
    }

    /**
     * Готовит сообщение к отправке - заменяет все подставновки в тексте (имя, константы, переменные)
     * @param User $user - пользователь
     * @param array $params - массив параметров для замены подстановок
     * @return $this
     */
    private function prepareMessage(User $user, array $params = [])
    {
        $this->message = str_replace('#NAME#', (new RSA())->decrypt($user->name, $user->private_key), $this->message);

        $constants = get_defined_constants(true);
        if (!empty($constants['user']) && is_array($constants['user'])) { // замена всех констант в шаблоне
            foreach ($constants['user'] as $key => $constant) {
                if (is_string($constant)) $this->message = str_replace('#' . mb_strtoupper($key) . '#', $constant, $this->message);
            }
        }

        if (!empty($params) && is_array($params)) { // замена всех переменных из массива подстановок
            foreach ($params as $key => $param) {
                $this->message = str_replace('#' . mb_strtoupper($key) . '#', $param, $this->message);
            }
        }

        return $this;
    }

    /**
     * Убирает лишние теги в сообщении в зависимости от типа рассылки (text/html)
     * @return $this
     */
    private function removeTags()
    {
        if (self::TYPE_HTML === $this->type) {
            $this->message = strip_tags(trim($this->message), '<p><div><span><b><strong><i><br><h1><h2><h3><h4><h5><h6><ul><ol><li><a><table><tr><th><td><caption>');
        }
        else {
            $this->message = strip_tags($this->message);
        }
        return $this;
    }

    /**
     * Отправляет электронное письмо
     * @return bool
     */
    public function send()
    {
        $contentType = 'Content-type: text/' . ($this->isHtml() ? 'html' : 'plain') . '; charset=utf-8';

        $this->headers =
            'MIME-Version: 1.0' . "\r\n" .
            $contentType . "\r\n" .
            'Content-Transfer-Encoding: 7bit' . "\r\n" .
            'From: ' . $this->fromName . ' ' . $this->from . "\r\n" .
            'Reply-To: ' . $this->replyTo . "\r\n" .
            'X-Mailer: PHP' . phpversion() . "\r\n";

        return mail(
            $this->to,
            "=?UTF-8?B?" . base64_encode($this->subject) . "?=",
            $this->message,
            $this->headers
        );
    }
}
