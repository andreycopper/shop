<?php
namespace System\Loggers;

use Psr\Log\AbstractLogger;

/**
 * Class Logger
 * @package App\System
 */
abstract class Logger extends AbstractLogger
{
    protected $directory;
    protected $resource;

    protected function __construct()
    {
        $logger = match (true) {
            $this instanceof AccessLogger => 'access',
            $this instanceof SystemLogger => 'system',
            $this instanceof WarningLogger => 'warning',
            default => 'error',
        };

        $loggerDir = DIR_LOGS . DIRECTORY_SEPARATOR . $logger;
        $yearDir = $loggerDir . DIRECTORY_SEPARATOR . date('Y');
        $monthDir = $yearDir . DIRECTORY_SEPARATOR . date('m');

        if (!is_dir($loggerDir)) mkdir($loggerDir);
        if (!is_dir($yearDir)) mkdir($yearDir);
        if (!is_dir($monthDir)) mkdir($monthDir);

        $this->directory = $monthDir . DIRECTORY_SEPARATOR;
    }

    /**
     * Формирует строку с описанием пойманного исключения и записывает ее в лог-файл
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $date = date('Y-m-d H:i:s');
        $level = ucfirst($level);
        $log = "[{$date}] {$level}: $message IP: {$_SERVER['REMOTE_ADDR']}\n";

        if (!empty($context) && is_array($context)) {
            foreach ($context as $item) {
                $log .= $item . "\n";
            }
        }

        $log .= "==================================================\n";
        fwrite($this->resource, $log);
    }
}
