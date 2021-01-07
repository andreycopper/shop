<?php

namespace App\System;

use App\Traits\Singleton;

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
        $this->data = require CONFIG . '/config.php';
    }
}
