<?php
require __DIR__ . '/../config/autoload.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/constants.php';
require __DIR__ . '/../app/System/ErrorSupervisor.php';

session_start();

use System\Request;
use System\Response;
use System\Route;
use System\Logger;
use System\Security;
use Controllers\Errors;
use Exceptions\DbException;
use Exceptions\EditException;
use Exceptions\MailException;
use Exceptions\UserException;
use Exceptions\DeleteException;
use Exceptions\UploadException;
use System\Loggers\SystemLogger;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

Security::array_xss_clean($_GET);
Security::array_xss_clean($_POST);

Route::parseUrl($_SERVER['REQUEST_URI']);

try {
    Route::start();
} catch (Exception $e) {
    (new Errors($e))->action('actionError');
} catch(TypeError $e) {
    SystemLogger::getInstance()->error($e);
    (new Errors(new NotFoundException()))->action('actionError');
}

session_destroy();
