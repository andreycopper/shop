<?php
namespace System\Loggers;

use Traits\Singleton;

/**
 * Системные события
 * Class ErrorLogger
 * @package System\Loggers
 */
class SystemLogger extends Logger
{
    use Singleton;

    protected function __construct()
    {
        parent::__construct();
        $this->resource = fopen($this->directory . CONFIG['log']['system'], 'a');
    }
}
