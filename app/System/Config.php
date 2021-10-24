<?php

namespace System;

use Traits\Singleton;

/**
 * Class Config
 * @package App\System
 */
class Config
{
    public $data = [];

    use Singleton;

    private function __construct()
    {
        $this->data = require _CONFIG . '/config.php';
    }
}
