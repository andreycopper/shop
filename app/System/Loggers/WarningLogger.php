<?php
namespace System\Loggers;

use Traits\Singleton;

/**
 * Некритичные программные события
 * Class ErrorLogger
 * @package System\Loggers
 */
class WarningLogger extends Logger
{
    use Singleton;

    protected function __construct()
    {
        parent::__construct();
        $this->resource = fopen($this->directory . CONFIG['log']['warning'], 'a');
    }
}
