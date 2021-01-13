<?php

use App\Models\Setting;
use App\Exceptions\DbException;

define('ROOT', __DIR__ . '/..');
define('APP', __DIR__ . '/../app');
define('CONFIG', __DIR__ . '/../config');
define('VENDOR', __DIR__ . '/../vendor');
define('LOGS', __DIR__ . '/../logs');
define('PUBLIC', __DIR__ . '/../public');
define('VIEWS', __DIR__ . '/../views');
define('TEMPLATES', __DIR__ . '/../views/templates');

try {
    $constants = Setting::getList();

    if (!empty($constants) && is_array($constants)) {
        foreach ($constants as $constant) {
            define(strtoupper($constant->name), $constant->value);
        }
    }
} catch (DbException $e) {
}
