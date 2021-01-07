<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/constants.php';

use App\System\Route;
use App\Controllers\Errors;
use App\Exceptions\DbException;
use App\Exceptions\EditException;
use App\Exceptions\MailException;
use App\Exceptions\UserException;
use App\Exceptions\DeleteException;
use App\Exceptions\UploadException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ForbiddenException;

Route::parseUrl($_SERVER['REQUEST_URI']);

try {
    Route::start();

} catch (DbException $e) {

    $error = new Errors();
    $error->error = $e;
    $error->action('action500');

} catch (NotFoundException $e) {

    $error = new Errors();
    $error->error = $e;
    $error->action('action404');

} catch (ForbiddenException $e) {

    $error = new Errors();
    $error->error = $e;
    $error->action('action403');

} catch (DeleteException | EditException | MailException | UploadException | UserException $e) {

    $error = new Errors();
    $error->error = $e;
    $error->action('action400');
}

session_destroy();
