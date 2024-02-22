<?php
namespace System\Loggers;

use Traits\Singleton;

/**
 * Критические программные события
 * Class ErrorLogger
 * @package System\Loggers
 */
class ErrorLogger extends Logger
{
    use Singleton;

    protected function __construct()
    {
        parent::__construct();
        $this->resource = fopen($this->directory . CONFIG['log']['error'], 'a');
    }
}
