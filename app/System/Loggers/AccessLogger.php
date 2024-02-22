<?php
namespace System\Loggers;

use Traits\Singleton;

/**
 * События авторизации
 * Class AccessLogger
 * @package System\Loggers
 */
class AccessLogger extends Logger
{
    use Singleton;

    protected function __construct()
    {
        parent::__construct();
        $this->resource = fopen($this->directory . CONFIG['log']['access'], 'a');
    }
}
