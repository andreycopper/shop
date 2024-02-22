<?php

use System\Config;
use Models\Setting;
use Exceptions\DbException;
use System\Loggers\ErrorLogger;

const DIR_ROOT = __DIR__ . DIRECTORY_SEPARATOR . '..';
const DIR_APP = DIR_ROOT . DIRECTORY_SEPARATOR . 'app';
const DIR_CONFIG = DIR_ROOT . DIRECTORY_SEPARATOR . 'config';
const DIR_CACHE = DIR_ROOT . DIRECTORY_SEPARATOR. 'cache';
const DIR_VENDOR = DIR_ROOT . DIRECTORY_SEPARATOR . 'vendor';
const DIR_LOGS = DIR_ROOT . DIRECTORY_SEPARATOR . 'logs';
const DIR_VIEWS = DIR_ROOT . DIRECTORY_SEPARATOR . 'views';
const DIR_TEMPLATES = DIR_ROOT . DIRECTORY_SEPARATOR . 'views/templates';
const DIR_CERTIFICATES = DIR_ROOT . DIRECTORY_SEPARATOR . 'certificates';
const DIR_PUBLIC = DIR_ROOT . DIRECTORY_SEPARATOR . 'public';
const DIR_FILES = DIR_PUBLIC . DIRECTORY_SEPARATOR . 'files';
const DIR_IMAGES = DIR_PUBLIC . DIRECTORY_SEPARATOR . 'images';
const DIR_UPLOADS = DIR_PUBLIC . DIRECTORY_SEPARATOR . 'uploads';
define("TABLE", Config::getInstance()->data['db']['dbname']);
define("CONFIG", Config::getInstance()->data);

if (!is_dir(DIR_CACHE)) mkdir(DIR_CACHE);
if (!is_dir(DIR_LOGS)) mkdir(DIR_LOGS);

try {
    $constants = Setting::getSiteSettings();
}
catch (DbException $e) {
    ErrorLogger::getInstance()->error($e);
    echo 'Нет соединения в базой данных. Попробуйте позже.';
    die;
}

define("PROTOCOL", $constants['protocol'] ?: '');
define("DOMAIN", $constants['domain'] ?: '');
const SITE_URL = PROTOCOL . DOMAIN;

define("SITENAME", $constants['sitename'] ?: '');
define("SLOGAN", $constants['slogan'] ?: '');
define("EMAIL", $constants['email'] ?: '');
define("PHONE", $constants['phone'] ?: '');

define("CITY", $constants['city'] ?: '');
define("ADDRESS", $constants['address'] ?: '');
define("ADDRESS_JURIDICAL", $constants['address_juridical'] ?: '');
define("INN", $constants['inn'] ?: '');
define("OGRN", $constants['ogrn'] ?: '');
define("WORKDAYS", $constants['workdays'] ?: '');
define("WORKTIME", $constants['worktime'] ?: '');

define("TEMPLATE", $constants['template'] ?: '');
define("CHARSET", $constants['charset'] ?: '');
define("AUTH_DAYS", $constants['auth_days'] ?: '');

define("CURRENCY", $constants['currency'] ?: '');
define("CART_SUMMARY_HEADER", $constants['cart_summary_header'] ?: '');
define("CART_SUMMARY_FOOTER", $constants['cart_summary_footer'] ?: '');
define("SHOW_ALL_PRICES_CATALOG", $constants['show_all_prices_catalog'] ?: '');
define("SHOW_ALL_PRICES_PRODUCT", $constants['show_all_prices_product'] ?: '');
define("SHOW_PRICE_TYPES", $constants['show_price_types'] ?: '');

define("TELEGRAM", $constants['telegram'] ?: '');
define("WHATSAPP", $constants['whatsapp'] ?: '');
define("VK", $constants['vk'] ?: '');
define("FACEBOOK", $constants['facebook'] ?: '');
define("OK", $constants['ok'] ?: '');
define("TWITTER", $constants['twitter'] ?: '');
define("INSTAGRAM", $constants['instagram'] ?: '');
define("YOUTUBE", $constants['youtube'] ?: '');
define("GOOGLE", $constants['google'] ?: '');
define("MAIL", $constants['mail'] ?: '');

setcookie('user', $_COOKIE['user'] ?? hash('sha256', microtime(true) . uniqid()), time() + 60 * 60 * 24 * 365, '/', SITE_URL, 0); // кука анонимного юзера
