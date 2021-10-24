<?php
require __DIR__ . '/../config/autoload.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/constants.php';

session_start();

use System\Route;
use System\Logger;
use Controllers\Errors;
use Exceptions\DbException;
use Exceptions\EditException;
use Exceptions\MailException;
use Exceptions\UserException;
use Exceptions\DeleteException;
use Exceptions\UploadException;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

try {
    Route::start();
}
catch (DbException $e) {
    Logger::getInstance()->error($e);
    (new Errors($e))->action('action500');
}
catch (NotFoundException $e) {
    Logger::getInstance()->error($e);
    (new Errors($e))->action('action404');
}
catch (ForbiddenException $e) {
    Logger::getInstance()->error($e);
    (new Errors($e))->action('action403');
}
catch (DeleteException | EditException | MailException | UploadException | UserException $e) {
    Logger::getInstance()->error($e);
    (new Errors($e))->action('action400');
}

session_destroy();
