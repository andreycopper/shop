<?php
require __DIR__ . '/../config/autoload.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/constants.php';

session_start();

use System\Route;
use Controllers\Errors;
use Exceptions\DbException;
use Exceptions\EditException;
use Exceptions\MailException;
use Exceptions\UserException;
use Exceptions\DeleteException;
use Exceptions\UploadException;
use Exceptions\NotFoundException;
use Exceptions\ForbiddenException;

Route::parseUrl($_SERVER['REQUEST_URI']);

try {
    Route::start();

} catch (DbException $e) {
    (new Errors($e))->action('action500');
}
catch (NotFoundException $e) {
    (new Errors($e))->action('action404');
}
catch (ForbiddenException $e) {
    (new Errors($e))->action('action403');
}
catch (DeleteException | EditException | MailException | UploadException | UserException $e) {
    (new Errors($e))->action('action400');
}

session_destroy();
