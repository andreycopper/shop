<?php

namespace App\System;

use App\Traits\Singleton;
use Psr\Log\AbstractLogger;

/**
 * Class Access
 * @package App\System
 */
class Access extends AbstractLogger
{
    use Singleton;

    protected $res;

    protected function __construct()
    {
        $config = Config::getInstance()->data;
        $this->res = fopen($config['log']['access'], 'a');
    }

    /**
     * Формирует строку с описанием пойманного исключения и записывает ее в лог-файл
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $log = '[' . date('Y-m-d H:i:s') . '] ' . ucfirst($level) . ': ' . (string)$message . "\n";
        foreach ($context as $item) {
            $log .= (string)$item . "\n";
        }
        fwrite($this->res, $log);
    }
}
