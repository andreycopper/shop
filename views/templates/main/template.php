<?php
use Models\User\User as ModelUser;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>
        <?= SITENAME ?> | <?= $page->name ?? '' ?>
    </title>
    <meta name="keywords" content="<?= $page->meta_k ?? SLOGAN ?>" />
    <meta name="description" content="<?= $page->meta_d ?? SLOGAN ?>" />

    <link rel="stylesheet" href="/css/styles.css?style=<?= microtime() ?>" media="all">
    <link rel="stylesheet" href="/css/media.css" media="all">
    <link rel="stylesheet" href="/css/loader.css" media="all">
    <link rel="stylesheet" href="/css/fontawesome.min.css" media="all">
    <link rel="stylesheet" href="/css/lightbox.css" media="all">
    <link rel="stylesheet" href="/css/slick-theme.css" media="all">
    <link rel="stylesheet" href="/css/slick.css" media="all">
    <link rel="stylesheet" href="/css/jquery.mCustomScrollbar.css" media="all">

    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="/js/jquery-ui.js"></script>
    <script src="/js/lightbox.min.js"></script>
    <script src="/js/slick.min.js"></script>
    <script src="/js/jquery.cookie.js"></script>
    <script src="/js/ondelay.jquery.js"></script>
    <script src="/js/jquery.autocomplete.min.js"></script>
    <script src="/js/jquery.inputmask.js"></script>
    <script src="/js/cities.js"></script>
    <script src="/js/functions.js"></script>
    <script src="/js/scripts.js"></script>

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
<header>
    <div class="header">
        <div class="header-row container">
            <button class="hamburger hamburger--htx">
                <span>toggle menu</span>
            </button>

            <div class="header-logo">
                <a href="/">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 190 40">
                        <rect class="header-logo-1" width="40" height="40" rx="6" ry="6"/>
                        <path class="header-logo-2" d="M53.986,29.989h4.021V16.664L68.634,29.989H72v-21H68V21.934L57.708,8.985H53.986v21ZM90.172,14H95.05l2.956,4.611L100.991,14h4.758l-5.378,7.755,5.66,8.235H101.15l-3.234-5-3.234,5H89.921l5.629-8.145Zm22.818,3.138v7.582a1.544,1.544,0,0,0,1.654,1.682,5.58,5.58,0,0,0,1.891-.4H117v3.186a6.472,6.472,0,0,1-3.328.823c-2.785,0-4.671-1.108-4.671-4.821V17.138h-1.979L107,14h2V10h4v4h4l-0.019,3.138H112.99Zm25.022,12.851h3.982V21.6c0-2.156,1.281-3.523,3.018-3.523S148,19.386,148,21.554v8.436h3.99V19.9c0-3.683-1.85-5.916-5.294-5.916a5.493,5.493,0,0,0-4.7,2.316V8h-3.982V29.989ZM163.586,30h-2.193a8.039,8.039,0,0,1-.035-16h2.381A8.039,8.039,0,0,1,163.586,30Zm-1.1-12.292a3.992,3.992,0,0,0-4.024,4.285,4.123,4.123,0,0,0,4.082,4.285,3.992,3.992,0,0,0,4.024-4.285A4.123,4.123,0,0,0,162.49,17.708Zm10.53,17.278h3.989l-0.031-7.093a6.144,6.144,0,0,0,5.012,2.083c3.66,0,7.033-2.825,7.033-7.984,0-5.217-3.43-8.041-7.033-8.041a5.81,5.81,0,0,0-5.012,2.294L177.009,14h-4.021Zm7.942-8.56a4.134,4.134,0,0,1-4.044-4.432c0-2.725,1.835-4.492,4.044-4.492a4.157,4.157,0,0,1,4.074,4.432A4.158,4.158,0,0,1,180.962,26.426Zm-52.774,3.6c4.512,0,7.68-2.278,7.68-6.338,0-3.622-2.391-5.111-6.634-6.192-3.616-.905-4.512-1.344-4.512-2.687,0-1.051.926-1.84,2.689-1.84a9.955,9.955,0,0,1,5.439,2.015l2.39-3.388a12.284,12.284,0,0,0-7.769-2.6c-4.273,0-7.321,2.453-7.321,6.162,0,4.118,2.719,5.257,6.932,6.308,3.5,0.876,4.214,1.46,4.214,2.6,0,1.256-1.136,1.986-3.018,1.986a9.717,9.717,0,0,1-6.246-2.482l-2.719,3.183A13.492,13.492,0,0,0,128.188,30.023Zm-45.817.087A8.12,8.12,0,0,0,88.928,27.2l-2.605-1.972a5.335,5.335,0,0,1-3.893,1.6,3.7,3.7,0,0,1-3.923-3.057H89.677c0.03-.408.06-0.971,0.06-1.321,0-4.425-2.455-8.472-7.815-8.472A7.869,7.869,0,0,0,73.986,22.1C73.986,26.934,77.55,30.11,82.371,30.11Zm-3.923-9.261c0.359-2.009,1.587-3.319,3.474-3.319,1.916,0,3.114,1.339,3.384,3.319H78.448Z"/>
                        <path class="header-logo-3" d="M14,11V29a3,3,0,0,1-6,0V11h6Z"/>
                        <path class="header-logo-4" d="M14,23.428L8,11.365V11h6V23.428Z"/>
                        <path class="header-logo-3" d="M29,8a3,3,0,0,1,3,3v6a3,3,0,0,1-6,0V11A3,3,0,0,1,29,8ZM8.871,8.871a3.045,3.045,0,0,1,4.306,0L31.1,26.791A3.045,3.045,0,0,1,26.791,31.1L8.871,13.178A3.045,3.045,0,0,1,8.871,8.871Z"/>
                    </svg>
                </a>
            </div>

            <div class="header-slogan">
                <div class="header-slogan-container"><?= SLOGAN ?></div>
            </div>

            <div class="header-city">
                <div class="header-city-title">Ваш город</div>

                <div class="header-city-choice">
                    <a class="header-city-link header-action" data-target="location">
                        <?= $this->location->name ?? 'Москва' ?>
                    </a>
                    <span></span>
                </div>
            </div>

            <div class="header-phone">
                <div class="header-phone-block">
                    <i class="header-phone-pic"></i>
                    <a href="tel:<?= PHONE ?>" class="header-phone-link" rel="nofollow"><?= PHONE ?></a>
                </div>

                <div class="header-callback">
                    <a href="" class="header-callback-link header-action" data-target="callback">Заказать звонок</a>
                </div>
            </div>

            <div class="header-search">
                <a href="/search/" class="header-search-link header-action" data-target="search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                        <path class="header-search-pic" d="M7.5,0A7.5,7.5,0,1,1,0,7.5,7.5,7.5,0,0,1,7.5,0Zm0,2A5.5,5.5,0,1,1,2,7.5,5.5,5.5,0,0,1,7.5,2Z"></path>
                        <path class="header-search-pic" d="M13.417,12.035l3.3,3.3a0.978,0.978,0,1,1-1.382,1.382l-3.3-3.3A0.978,0.978,0,0,1,13.417,12.035Z"></path>
                    </svg>
                </a>
            </div>

            <div class="header-compare">
                <a href="/compare/" class="header-compare-link info" data-target="compare" data-url="/catalog/compare/" title="Сравнение">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21">
                        <path class="header-compare-pic" d="M20,4h0a1,1,0,0,1,1,1V21H19V5A1,1,0,0,1,20,4ZM14,7h0a1,1,0,0,1,1,1V21H13V8A1,1,0,0,1,14,7ZM8,1A1,1,0,0,1,9,2V21H7V2A1,1,0,0,1,8,1ZM2,9H2a1,1,0,0,1,1,1V21H1V10A1,1,0,0,1,2,9ZM0,0H1V1H0V0ZM0,0H1V1H0V0Z"></path>
                    </svg>

                    <span class="header-compare-count empty">0</span>
                </a>
            </div>

            <div class="header-favorite">
                <a href="/favorite" class="header-favorite-link info" data-target="favorites" data-url="/catalog/favorites/" title="Избранное">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22.969" height="21" viewBox="0 0 22.969 21">
                        <path class="header-favorite-pic" d="M21.028,10.68L11.721,20H11.339L2.081,10.79A6.19,6.19,0,0,1,6.178,0a6.118,6.118,0,0,1,5.383,3.259A6.081,6.081,0,0,1,23.032,6.147,6.142,6.142,0,0,1,21.028,10.68ZM19.861,9.172h0l-8.176,8.163H11.369L3.278,9.29l0.01-.009A4.276,4.276,0,0,1,6.277,1.986,4.2,4.2,0,0,1,9.632,3.676l0.012-.01,0.064,0.1c0.077,0.107.142,0.22,0.208,0.334l1.692,2.716,1.479-2.462a4.23,4.23,0,0,1,.39-0.65l0.036-.06L13.52,3.653a4.173,4.173,0,0,1,3.326-1.672A4.243,4.243,0,0,1,19.861,9.172ZM22,20h1v1H22V20Zm0,0h1v1H22V20Z" transform="translate(-0.031)"></path>
                    </svg>

                    <span class="header-favorite-count empty">0</span>
                </a>
            </div>

            <div class="header-cart">
                <a href="/cart/" class="header-cart-link" title="Корзина">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="21" viewBox="0 0 22 21">
                        <path class="header-cart-pic" d="M1507,122l-0.99,1.009L1492,123l-1-1-1-9h-3a0.88,0.88,0,0,1-1-1,1.059,1.059,0,0,1,1.22-1h2.45c0.31,0,.63.006,0.63,0.006a1.272,1.272,0,0,1,1.4.917l0.41,3.077H1507l1,1v1ZM1492.24,117l0.43,3.995h12.69l0.82-4Zm2.27,7.989a3.5,3.5,0,1,1-3.5,3.5A3.495,3.495,0,0,1,1494.51,124.993Zm8.99,0a3.5,3.5,0,1,1-3.49,3.5A3.5,3.5,0,0,1,1503.5,124.993Zm-9,2.006a1.5,1.5,0,1,1-1.5,1.5A1.5,1.5,0,0,1,1494.5,127Zm9,0a1.5,1.5,0,1,1-1.5,1.5A1.5,1.5,0,0,1,1503.5,127Z" transform="translate(-1486 -111)"></path>
                    </svg>

                    <span class="header-cart-count <?= isset($this->cartCount) && $this->cartCount > 0 ? '' : 'empty' ?>">
                        <?= $this->cartCount ?: 0 ?>
                    </span>
                </a>
            </div>

            <div class="header-user">
                <?php if (ModelUser::isAuthorized()): ?>
                    <a href="/personal/" class="header-user-link" title="Личный кабинет">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21">
                            <path class="header-user-pic" d="M13.969,16a1,1,0,1,1-2,0H11.927C11.578,14.307,9.518,13,7,13s-4.575,1.3-4.924,3H2.031a1,1,0,0,1-2,0,0.983,0.983,0,0,1,.1-0.424C0.7,12.984,3.54,11,7,11S13.332,13,13.882,15.6a1.023,1.023,0,0,1,.038.158c0.014,0.082.048,0.159,0.058,0.243H13.969ZM7,10a5,5,0,1,1,5-5A5,5,0,0,1,7,10ZM7,2a3,3,0,1,0,3,3A3,3,0,0,0,7,2Z"></path>
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="/personal/" class="header-user-link header-action" data-target="auth" title="Личный кабинет">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21">
                            <path class="header-user-pic" d="M1433,132h-15a3,3,0,0,1-3-3v-7a3,3,0,0,1,3-3h1v-2a6,6,0,0,1,6-6h1a6,6,0,0,1,6,6v2h1a3,3,0,0,1,3,3v7A3,3,0,0,1,1433,132Zm-3-15a4,4,0,0,0-4-4h-1a4,4,0,0,0-4,4v2h9v-2Zm4,5a1,1,0,0,0-1-1h-15a1,1,0,0,0-1,1v7a1,1,0,0,0,1,1h15a1,1,0,0,0,1-1v-7Zm-8,3.9v1.6a0.5,0.5,0,1,1-1,0v-1.6A1.5,1.5,0,1,1,1426,125.9Z" transform="translate(-1415 -111)"></path>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <nav>
        <div class="nav container">
            <?= $this->render('menu/catalog') ?>

            <?= $this->render('menu/main') ?>
        </div>
    </nav>
</header>

<section class="main">
    <div class="container">
        <?php //if (!empty(URL)): ?>
            <div class="main-header">
                <?= $this->render('breadcrumbs') ?>

                <h1><?= $item->name ?? $page->name ?? '' ?></h1>
            </div>
        <?php //endif; ?>

        <?= $view ?? '' ?>
    </div>
</section>

<footer>
    <div class="footer-top">
        <div class="container">
            <div class="footer-top-container">
                <div class="footer-logo">
                    <a href="/">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 190 40">
                            <rect class="footer-logo-1" width="40" height="40" rx="6" ry="6"/>
                            <path class="footer-logo-2" d="M53.986,29.989h4.021V16.664L68.634,29.989H72v-21H68V21.934L57.708,8.985H53.986v21ZM90.172,14H95.05l2.956,4.611L100.991,14h4.758l-5.378,7.755,5.66,8.235H101.15l-3.234-5-3.234,5H89.921l5.629-8.145Zm22.818,3.138v7.582a1.544,1.544,0,0,0,1.654,1.682,5.58,5.58,0,0,0,1.891-.4H117v3.186a6.472,6.472,0,0,1-3.328.823c-2.785,0-4.671-1.108-4.671-4.821V17.138h-1.979L107,14h2V10h4v4h4l-0.019,3.138H112.99Zm25.022,12.851h3.982V21.6c0-2.156,1.281-3.523,3.018-3.523S148,19.386,148,21.554v8.436h3.99V19.9c0-3.683-1.85-5.916-5.294-5.916a5.493,5.493,0,0,0-4.7,2.316V8h-3.982V29.989ZM163.586,30h-2.193a8.039,8.039,0,0,1-.035-16h2.381A8.039,8.039,0,0,1,163.586,30Zm-1.1-12.292a3.992,3.992,0,0,0-4.024,4.285,4.123,4.123,0,0,0,4.082,4.285,3.992,3.992,0,0,0,4.024-4.285A4.123,4.123,0,0,0,162.49,17.708Zm10.53,17.278h3.989l-0.031-7.093a6.144,6.144,0,0,0,5.012,2.083c3.66,0,7.033-2.825,7.033-7.984,0-5.217-3.43-8.041-7.033-8.041a5.81,5.81,0,0,0-5.012,2.294L177.009,14h-4.021Zm7.942-8.56a4.134,4.134,0,0,1-4.044-4.432c0-2.725,1.835-4.492,4.044-4.492a4.157,4.157,0,0,1,4.074,4.432A4.158,4.158,0,0,1,180.962,26.426Zm-52.774,3.6c4.512,0,7.68-2.278,7.68-6.338,0-3.622-2.391-5.111-6.634-6.192-3.616-.905-4.512-1.344-4.512-2.687,0-1.051.926-1.84,2.689-1.84a9.955,9.955,0,0,1,5.439,2.015l2.39-3.388a12.284,12.284,0,0,0-7.769-2.6c-4.273,0-7.321,2.453-7.321,6.162,0,4.118,2.719,5.257,6.932,6.308,3.5,0.876,4.214,1.46,4.214,2.6,0,1.256-1.136,1.986-3.018,1.986a9.717,9.717,0,0,1-6.246-2.482l-2.719,3.183A13.492,13.492,0,0,0,128.188,30.023Zm-45.817.087A8.12,8.12,0,0,0,88.928,27.2l-2.605-1.972a5.335,5.335,0,0,1-3.893,1.6,3.7,3.7,0,0,1-3.923-3.057H89.677c0.03-.408.06-0.971,0.06-1.321,0-4.425-2.455-8.472-7.815-8.472A7.869,7.869,0,0,0,73.986,22.1C73.986,26.934,77.55,30.11,82.371,30.11Zm-3.923-9.261c0.359-2.009,1.587-3.319,3.474-3.319,1.916,0,3.114,1.339,3.384,3.319H78.448Z"/>
                            <path class="footer-logo-3" d="M14,11V29a3,3,0,0,1-6,0V11h6Z"/>
                            <path class="footer-logo-4" d="M14,23.428L8,11.365V11h6V23.428Z"/>
                            <path class="footer-logo-3" d="M29,8a3,3,0,0,1,3,3v6a3,3,0,0,1-6,0V11A3,3,0,0,1,29,8ZM8.871,8.871a3.045,3.045,0,0,1,4.306,0L31.1,26.791A3.045,3.045,0,0,1,26.791,31.1L8.871,13.178A3.045,3.045,0,0,1,8.871,8.871Z"/>
                        </svg>
                    </a>
                </div>

                <div class="footer-subscribe">
                    <div class="footer-subscribe-description">
                        Будьте в курсе новостей и акций. Подпишитесь на нашу рассылку
                    </div>

                    <div class="footer-subscribe-field">
                        <form action="" class="footer-subscribe-form">
                            <label for="footer-subscribe" class="footer-subscribe-label">Введите Email</label>
                            <input type="text" class="footer-subscribe-input" id="footer-subscribe">
                            <input type="submit" class="footer-subscribe-submit" value="Подписаться">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-middle">
        <div class="container">
            <div class="footer-middle-container">
                <?= $this->render('menu/footer') ?>

                <div class="footer-contacts">
                    <a href="/contacts/" class="footer-title">Наши контакты</a>

                    <ul>
                        <li>
                            <a href="tel:<?= PHONE ?>" class="footer-phone" rel="nofollow"><?= PHONE ?></a>
                        </li>
                        <li>
                            <a href="mailto:<?= EMAIL ?>" class="footer-mail"><?= EMAIL ?></a>
                        </li>
                        <li>
                            <div class="footer-address"><?= ADDRESS ?></div>
                        </li>
                    </ul>

                    <ul class="footer-social-icons">
                        <li class="vk"><a href="<?= VK ?>"></a></li>

                        <li class="facebook"><a href="<?= FACEBOOK ?>"></a></li>

                        <li class="twitter"><a href="<?= TWITTER ?>"></a></li>

                        <li class="instagram"><a href="<?= INSTAGRAM ?>"></a></li>

                        <li class="youtube"><a href="<?= YOUTUBE ?>"></a></li>

                        <li class="odkl"><a href="<?= OK ?>"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-container">
                <div class="footer-copyright">&copy; <?= date('Y') ?> <?= SITENAME ?></div>

                <div class="footer-counters">COUNTERS</div>

                <div class="footer-develop">Разработано - <a href="/" class="footer-develop-link">Art Studio</a></div>
            </div>
        </div>
    </div>
</footer>

<?= $this->render('menu/mobile') ?>

<div id="search" class="modal">
    <div class="overlay"></div>
    <div class="modal-content">
        <div class="close"></div>
        <form action="" method="get">
            <label>
                <input type="text" placeholder="Введите запрос и нажмите Enter" autofocus>
            </label>
            <button type="submit"></button>
        </form>
    </div>
</div>

<div id="callback" class="modal modalform">
    <div class="overlay"></div>
    <div class="modal-content">
        <div class="close"></div>
        <div class="title">
            <h2>Заказать звонок</h2>
        </div>
        <form action="/callBacks/save/" method="post">
            <label>
                Имя <span class="red">*</span>
                <input type="text" name="name" class="required">
                <span class="tooltip"></span>
            </label>
            <label>
                Телефон <span class="red">*</span>
                <input type="text" name="phone" class="required">
                <span class="tooltip"></span>
            </label>
            <label class="checkbox">
                <input type="checkbox" name="agreement" class="required">
                Я согласен на <a href="">обработку персональных данных</a> <span class="red">*</span>
            </label>
            <input type="submit" value="Отправить">
            <div class="message_error"></div>
        </form>
        <div class="message_success"></div>
    </div>
</div>

<div id="fast" class="modal">
    <div class="overlay"></div>
    <div class="modal-content">
        <div class="close"></div>
        <div class="content">
            Здесь будет быстрый просмотр товара
        </div>
    </div>
</div>

<div id="compare" class="modal">
    <div class="overlay"></div>
    <div class="modal-content">
        <div class="close"></div>
        <div class="title">
            <h2>Сравнение товаров</h2>
        </div>
        <div class="content">
            Здесь будет сравнение товаров
        </div>
    </div>
</div>

<div id="favorites" class="modal">
    <div class="overlay"></div>
    <div class="modal-content">
        <div class="close"></div>
        <div class="title">
            <h2>Избранное</h2>
        </div>
        <div class="content">
            Здесь будут избранные товары
        </div>
    </div>
</div>

<div id="qorder" class="modal modalform">
    <div class="overlay"></div>
    <div class="modal-content">
        <div class="close"></div>
        <div class="title">
            <h2>Быстрый заказ</h2>
        </div>
        <form action="/quickOrders/save/" method="post">
            <?php //if (!empty($item->id) && $item instanceof Models\Product\Product): ?>
                <input type="hidden" name="id" value="<?= $item->id ?? '' ?>">
                <input type="hidden" name="count" value="1">
            <?php //endif; ?>
            <label>
                Имя <span class="red">*</span>
                <input type="text" name="name" class="required">
                <span class="tooltip"></span>
            </label>
            <label>
                Телефон <span class="red">*</span>
                <input type="text" name="phone" class="required">
                <span class="tooltip"></span>
            </label>
            <label class="checkbox">
                <input type="checkbox" name="agreement" class="required">
                Я согласен на <a href="">обработку персональных данных</a> <span class="red">*</span>
            </label>
            <input type="submit" value="Отправить">
            <div class="message_error"></div>
        </form>
        <div class="message_success"></div>
    </div>
</div>

<div id="auth" class="modal">
    <div class="overlay"></div>
    <div class="modal-content">
        <div class="close"></div>
        <div class="title">
            <h2>Вход</h2>
        </div>
        <form action="/auth/" method="post">
            <label>
                Email / Телефон <span class="red">*</span>
                <input type="text" name="login" class="required">
                <span class="tooltip"></span>
            </label>
            <label>
                Пароль <span class="red">*</span>
                <input type="password" name="password" class="required">
                <span class="tooltip"></span>
            </label>
<!--            <label class="checkbox">-->
<!--                <input type="checkbox" name="personal_data" class="required">-->
<!--                Я согласен на <a href="">обработку персональных данных</a> <span class="red">*</span>-->
<!--                <span class="tooltip"></span>-->
<!--            </label>-->
            <label class="checkbox checked">
                <input type="checkbox" name="remember" checked>
                Запомнить меня
            </label>
            <input type="submit" name="send" value="Войти">
            <div class="message_error"></div>
            <div class="flex-wrap additional">
                <a href="/auth/restore/" class="textLeft">Забыли пароль?</a>
                <a href="/auth/registration/" class="textRight">Регистрация</a>
            </div>
        </form>
    </div>
</div>

<div id="location" class="modal cities">
    <div class="overlay"></div>
    <div class="modal-content">
        <div class="close" title="Закрыть"></div>
        <div class="reset" title="Сбросить"></div>

        <div class="title">
            <form method="get">
                <label>
                    Город
                    <input type="text" name="city" id="city" />
                </label>
                <span class="example">
                <a href="">Например, </a>
            </span>
            </form>
        </div>

        <div class="regions flex-wrap">
            <div class="region-block district">
                <span class="main">Федеральный округ</span>
                <ul>
                    <?php if (!empty($this->districts) && is_array($this->districts)):
                        foreach ($this->districts as $district): ?>
                            <li>
                                <a href="#" data-id="<?= $district->id ?>"><?= $district->name ?></a>
                            </li>
                        <?php endforeach;
                    endif; ?>
                </ul>
            </div>

            <div class="region-block region">
                <span class="main">Регион</span>
                <ul></ul>
            </div>

            <div class="region-block city">
                <span class="main">Город</span>
                <ul></ul>
            </div>
        </div>
    </div>
</div>

<div id="loader">
    <div class="footer-loader">
        <div class="loader-container" >
<!--            <div class="loader-message">Подождите, Ваши данные обрабатываются</div>-->
            <div class='sk-fading-circle'>
                <div class='sk-circle sk-circle-1'></div>
                <div class='sk-circle sk-circle-2'></div>
                <div class='sk-circle sk-circle-3'></div>
                <div class='sk-circle sk-circle-4'></div>
                <div class='sk-circle sk-circle-5'></div>
                <div class='sk-circle sk-circle-6'></div>
                <div class='sk-circle sk-circle-7'></div>
                <div class='sk-circle sk-circle-8'></div>
                <div class='sk-circle sk-circle-9'></div>
                <div class='sk-circle sk-circle-10'></div>
                <div class='sk-circle sk-circle-11'></div>
                <div class='sk-circle sk-circle-12'></div>
            </div>
        </div>
    </div>
</div>

<div id="notification" class=""></div>
</body>
</html>
