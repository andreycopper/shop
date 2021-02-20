<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Shop Modern</title>

    <link rel="stylesheet" href="/css/styles.css" media="all">
    <link rel="stylesheet" href="/css/media.css" media="all">
    <link rel="stylesheet" href="/css/loader.css" media="all">
    <link rel="stylesheet" href="/css/colors.css" media="all">
    <link rel="stylesheet" href="/css/fontawesome.min.css" media="all">
    <link rel="stylesheet" href="/css/jquery.mCustomScrollbar.css" media="all">

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
                <div class="header-slogan-container">
                    <?= SLOGAN ?>
                </div>
            </div>
            <div class="header-city">
                <div class="header-city-title">
                    Ваш город
                </div>
                <div class="header-city-choice">
                    <a class="header-city-link header-action" data-target="location">
                        <?= $this->location->city ?? 'Москва' ?>
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
                <a href="/compare/" class="header-compare-link header-action" data-target="compare" title="Сравнение">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21">
                        <path class="header-compare-pic" d="M20,4h0a1,1,0,0,1,1,1V21H19V5A1,1,0,0,1,20,4ZM14,7h0a1,1,0,0,1,1,1V21H13V8A1,1,0,0,1,14,7ZM8,1A1,1,0,0,1,9,2V21H7V2A1,1,0,0,1,8,1ZM2,9H2a1,1,0,0,1,1,1V21H1V10A1,1,0,0,1,2,9ZM0,0H1V1H0V0ZM0,0H1V1H0V0Z"></path>
                    </svg>
                    <span class="header-compare-count empty">0</span>
                </a>
            </div>
            <div class="header-favorite">
                <a href="/favorite" class="header-favorite-link header-action" data-target="favorite" title="Избранное">
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
                    <span class="header-cart-count <?= intval($this->cart_count) > 0 ? '' : 'empty' ?>"><?= $this->cart_count ?></span>
                </a>
            </div>
            <div class="header-user">
                <? if (!empty($user->id)): ?>
                    <a href="/personal/" class="header-user-link" title="Личный кабинет">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21">
                            <path class="header-user-pic" d="M13.969,16a1,1,0,1,1-2,0H11.927C11.578,14.307,9.518,13,7,13s-4.575,1.3-4.924,3H2.031a1,1,0,0,1-2,0,0.983,0.983,0,0,1,.1-0.424C0.7,12.984,3.54,11,7,11S13.332,13,13.882,15.6a1.023,1.023,0,0,1,.038.158c0.014,0.082.048,0.159,0.058,0.243H13.969ZM7,10a5,5,0,1,1,5-5A5,5,0,0,1,7,10ZM7,2a3,3,0,1,0,3,3A3,3,0,0,0,7,2Z"></path>
                        </svg>
                    </a>
                <? else: ?>
                    <a href="/personal/" class="header-user-link header-action" data-target="auth" title="Личный кабинет">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21">
                            <path class="header-user-pic" d="M1433,132h-15a3,3,0,0,1-3-3v-7a3,3,0,0,1,3-3h1v-2a6,6,0,0,1,6-6h1a6,6,0,0,1,6,6v2h1a3,3,0,0,1,3,3v7A3,3,0,0,1,1433,132Zm-3-15a4,4,0,0,0-4-4h-1a4,4,0,0,0-4,4v2h9v-2Zm4,5a1,1,0,0,0-1-1h-15a1,1,0,0,0-1,1v7a1,1,0,0,0,1,1h15a1,1,0,0,0,1-1v-7Zm-8,3.9v1.6a0.5,0.5,0,1,1-1,0v-1.6A1.5,1.5,0,1,1,1426,125.9Z" transform="translate(-1415 -111)"></path>
                        </svg>
                    </a>
                <? endif; ?>
            </div>
        </div>
    </div>
    <nav>
        <div class="nav container">
            <div class="nav-item nav-catalog-dropdown">
                <a href="/catalog/" class="nav-item-link nav-catalog">
                    Каталог
                    <span class="nav-item-bottom"></span>
                </a>
                <div class="nav-catalog-menu">
                    <ul class="nav-catalog-container">
                        <? if (!empty($this->groups)): ?>
                            <? foreach ($this->groups[0] as $group1): ?>
                                <li>
                                    <a href="/catalog/<?=$group1['name']?>/" class="nav-catalog-image">
                                        <img src="/uploads/groups/<?=$group1['id']?>/<?=$group1['image']?>" alt="">
                                    </a>
                                    <a href="/catalog/<?=$group1['name']?>/" class="nav-catalog-title"><?=$group1['title']?></a>
                                    <? if (!empty($this->groups[$group1['id']]) && is_array($this->groups[$group1['id']])): ?>
                                        <ul class="nav-catalog-submenu">
                                            <? foreach ($this->groups[$group1['id']] as $group2): ?>
                                                <li>
                                                    <a href="/catalog/<?=$group1['name']?>/<?=$group2['name']?>/"><?=$group2['title']?></a>
                                                    <? if (!empty($this->groups[$group2['id']]) && is_array($this->groups[$group2['id']])): ?>
                                                        <ul class="nav-catalog-submenu">
                                                            <? foreach ($this->groups[$group2['id']] as $group3): ?>
                                                                <li><a href="/catalog/<?=$group1['name']?>/<?=$group2['name']?>/<?=$group3['name']?>/"><?=$group3['title']?></a></li>
                                                            <? endforeach; ?>
                                                        </ul>
                                                    <? endif; ?>
                                                </li>
                                            <? endforeach; ?>
                                        </ul>
                                    <? endif; ?>
                                </li>
                            <? endforeach; ?>
                        <? endif; ?>
                    </ul>
                </div>
            </div>

            <? if (!empty($this->menu) && is_array($this->menu)): ?>
                <? foreach ($this->menu[0] as $menuItem): ?>
                    <div class="nav-item nav-wrap">
                        <a href="/<?=$menuItem['link']?>/" class="nav-item-link <?=!empty($this->menu[$menuItem['id']]) ? 'dropdown' : '';?>">
                            <?=$menuItem['name']?>
                            <span class="nav-item-bottom"></span>
                        </a>

                        <? if (!empty($this->menu[$menuItem['id']])) : ?>
                            <ul class="dropdown-menu">
                                <? foreach ($this->menu[$menuItem['id']] as $subMenuItem): ?>
                                    <li><a href="/<?=$menuItem['link']?>/<?=$subMenuItem['link']?>/"><?=$subMenuItem['name']?></a></li>
                                <? endforeach; ?>
                            </ul>
                        <? endif; ?>
                    </div>
                <? endforeach; ?>
            <? endif; ?>
        </div>
    </nav>
</header>

<section class="main">
    <?php echo $view ?? null; ?>
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
                <!-- FOOTER MENU -->
                <? if (!empty($this->menu[0])): ?>
                    <? foreach ($this->menu[0] as $footerMenuItem): ?>
                        <? if (!empty($footerMenuItem['footer'])): ?>
                            <div class="footer-menu">
                                <a href="/<?=$footerMenuItem['link']?>/" class="footer-title"><?=$footerMenuItem['name']?></a>

                                <? if (!empty($this->menu[$footerMenuItem['id']])): ?>
                                    <ul>
                                        <? foreach ($this->menu[$footerMenuItem['id']] as $footerSubMenuItem): ?>
                                            <? if (!empty($footerSubMenuItem['footer'])): ?>
                                                <li>
                                                    <a href="/<?=$footerMenuItem['link']?>/<?=$footerSubMenuItem['link']?>/" class="footer-link"><?=$footerSubMenuItem['name']?></a>
                                                </li>
                                            <? endif; ?>
                                        <? endforeach; ?>
                                    </ul>
                                <? endif; ?>
                            </div>
                        <? endif; ?>
                    <? endforeach; ?>
                <? endif; ?>
                <!-- FOOTER MENU -->

                <!-- FOOTER CONTACTS -->
                <div class="footer-contacts">
                    <a href="/contacts/" class="footer-title">Наши контакты</a>
                    <ul>
                        <li>
                            <a href="tel:+78002001010" class="footer-phone" rel="nofollow">8 (800) 200-10-10</a>
                        </li>
                        <li>
                            <a href="mailto:info@shop.ru" class="footer-mail">info@shop.ru</a>
                        </li>
                        <li>
                            <div class="footer-address">Новосибирск, ул. Гоголя 1а, 2 этаж, офис 14</div>
                        </li>
                    </ul>
                    <ul class="footer-social-icons">
                        <li class="vk">
                            <a href=""></a>
                        </li>
                        <li class="facebook">
                            <a href=""></a>
                        </li>
                        <li class="twitter">
                            <a href=""></a>
                        </li>
                        <li class="instagram">
                            <a href=""></a>
                        </li>
                        <li class="youtube">
                            <a href=""></a>
                        </li>
                        <li class="odkl">
                            <a href=""></a>
                        </li>
                    </ul>
                </div>
                <!-- FOOTER CONTACTS -->
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-container">
                <div class="footer-copyright">
                    &copy; 2019 Next Shop
                </div>

                <div class="footer-counters">
                    COUNTERS
                </div>

                <div class="footer-develop">
                    Разработано - <a href="/" class="footer-develop-link">Art Studio</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="overlay"></div>
<div class="menu-mobile">
    <div class="close"></div>
    <div class="menu-mobile-scroller" style="">
        <div class="menu-mobile-wrap" style="transform: translateX(0%);">
            <ul class="">
                <li class="menu-mobile-item">
                    <a href="/catalog/" class="menu-mobile-link parent" title="Каталог">
                        <span>Каталог</span>
                    </a>
                    <span class="menu-mobile-arrow"><i class="svg-triangle-right"></i></span>
                </li>

                <? if (!empty($this->menu) && is_array($this->menu)): ?>
                    <? foreach ($this->menu[0] as $menuItem): ?>
                        <li class="menu-mobile-item">
                            <a href="/<?=$menuItem['link']?>/" class="menu-mobile-link <?=!empty($this->menu[$menuItem['id']]) ? 'parent' : '';?>" title="<?=$menuItem['name']?>">
                                <span><?=$menuItem['name']?></span>
                            </a>
                            <? if (!empty($this->menu[$menuItem['id']])): ?>
                                <span class="menu-mobile-arrow"><i class="svg-triangle-right"></i></span>
                            <? endif; ?>
                        </li>
                    <? endforeach; ?>
                <? endif; ?>

                <li class="menu-mobile-item">
                    <a href="" class="menu-mobile-link menu-mobile-location parent mobile-action" rel="nofollow" data-target="location">
                        <span><?= $this->location->city ?? 'Москва' ?></span>
                    </a>
                    <i class="svg-address"></i>
                    <span class="menu-mobile-arrow"><i class="svg-triangle-right"></i></span>
                </li>
                <li class="menu-mobile-item">
                    <a href="/personal/" class="menu-mobile-link" rel="nofollow">
                        <i class="menu-mobile-cabinet" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17">
                                <path class="svg-mobile-cabinet" d="M14,17H2a2,2,0,0,1-2-2V8A2,2,0,0,1,2,6H3V4A4,4,0,0,1,7,0H9a4,4,0,0,1,4,4V6h1a2,2,0,0,1,2,2v7A2,2,0,0,1,14,17ZM11,4A2,2,0,0,0,9,2H7A2,2,0,0,0,5,4V6h6V4Zm3,4H2v7H14V8ZM8,9a1,1,0,0,1,1,1v2a1,1,0,0,1-2,0V10A1,1,0,0,1,8,9Z"></path>
                            </svg>
                        </i>
                        <span>Личный кабинет</span>
                    </a>
                </li>
                <li class="menu-mobile-item">
                    <a href="/basket/" class="menu-mobile-link menu-mobile-basket" rel="nofollow">
                        <span>
                            Корзина
                            <span class="menu-mobile-count <?= intval($this->cart_count) > 0 ? '' : 'empty' ?>"><?= $this->cart_count ?></span>
                        </span>
                    </a>
                    <i class="svg-basket"></i>
                </li>
                <li class="menu-mobile-item">
                    <a href="/basket/#delayed" class="menu-mobile-link menu-mobile-wish" rel="nofollow">
                        <span>
                            Отложенные
                            <span class="menu-mobile-count empty">0</span>
                        </span>
                    </a>
                    <i class="svg-wish"></i>
                </li>
                <li class="menu-mobile-item">
                    <a href="/catalog/compare.php" class="menu-mobile-link menu-mobile-compare" rel="nofollow">
                        <span>
                            Сравнение товаров
                            <span class="menu-mobile-count empty">0</span>
                        </span>
                    </a>
                    <i class="svg-compare"></i>
                </li>
                <li class="menu-mobile-item">
                    <a href="tel:<?= PHONE ?>" class="menu-mobile-link menu-mobile-phone" rel="nofollow">
                        <span><?= PHONE ?></span>
                    </a>
                    <i class="svg-phone"></i>
                </li>
            </ul>
            <div class="menu-mobile-contacts">
                <div class="menu-mobile-title">Контактная информация</div>
                <div class="menu-mobile-address">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="16" viewBox="0 0 13 16">
                        <path data-name="Ellipse 74 copy" class="svg-inline-address" d="M763.9,42.916h0.03L759,49h-1l-4.933-6.084h0.03a6.262,6.262,0,0,1-1.1-3.541,6.5,6.5,0,0,1,13,0A6.262,6.262,0,0,1,763.9,42.916ZM758.5,35a4.5,4.5,0,0,0-3.741,7h-0.012l3.542,4.447h0.422L762.289,42H762.24A4.5,4.5,0,0,0,758.5,35Zm0,6a1.5,1.5,0,1,1,1.5-1.5A1.5,1.5,0,0,1,758.5,41Z" transform="translate(-752 -33)"></path>
                    </svg>
                    <?= CITY ?>, <?= ADDRESS ?>
                </div>
                <div class="menu-mobile-email">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="13" viewBox="0 0 16 13">
                        <path class="svg-inline-email" d="M14,13H2a2,2,0,0,1-2-2V2A2,2,0,0,1,2,0H14a2,2,0,0,1,2,2v9A2,2,0,0,1,14,13ZM3.534,2L8.015,6.482,12.5,2H3.534ZM14,3.5L8.827,8.671a1.047,1.047,0,0,1-.812.3,1.047,1.047,0,0,1-.811-0.3L2,3.467V11H14V3.5Z"></path>
                    </svg>
                    <a href="mailto:info@msk.next.aspro-demo.ru"><?= EMAIL ?></a>
                </div>
            </div>
            <div class="menu-mobile-social">
                <ul>
                    <li class="mobile-social-item">
                        <a href="" class="mobile-social-link" target="_blank" rel="nofollow" title="Facebook">Facebook</a>
                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-inline-fb" width="20" height="20" viewBox="0 0 20 20">
                            <path class="cls-1" d="M12.988,5.981L13.3,4a15.921,15.921,0,0,0-2.4.019,2.25,2.25,0,0,0-1.427.784A2.462,2.462,0,0,0,9,6.4C9,7.091,9,8.995,9,8.995L7,8.981v2.006l2,0.008v6l2.013,0v-6l2.374,0L13.7,8.979H11.012s0-2.285,0-2.509a0.561,0.561,0,0,1,.67-0.486C12.122,5.98,12.988,5.981,12.988,5.981Z"></path>
                        </svg>
                    </li>

                    <li class="mobile-social-item">
                        <a href="" class="mobile-social-link" target="_blank" rel="nofollow" title="Вконтакте">Вконтакте</a>
                        <svg xmlns="http://www.w3.org/2000/svg" class="svg-inline-vk" width="20" height="20" viewBox="0 0 20 20">
                            <path class="cls-1" d="M10.994,6.771v3.257a0.521,0.521,0,0,0,.426.351c0.3,0,.978-1.8,1.279-2.406a1.931,1.931,0,0,1,.738-0.826A0.61,0.61,0,0,1,13.8,6.992h2.878a0.464,0.464,0,0,1,.3.727,29.378,29.378,0,0,1-2.255,2.736,1.315,1.315,0,0,0-.238.55,1.2,1.2,0,0,0,.313.627c0.2,0.226,1.816,2,1.966,2.155a1.194,1.194,0,0,1,.276.576,0.765,0.765,0,0,1-.8.614c-0.627,0-2.167,0-2.342,0a2.788,2.788,0,0,1-.952-0.565c-0.226-.2-1.153-1.152-1.278-1.277a2.457,2.457,0,0,0,.024-0.363,0.826,0.826,0,0,0-.7.8,4.083,4.083,0,0,1-.238,1.139,1.024,1.024,0,0,1-.737.275A5,5,0,0,1,7.1,14.262,14.339,14.339,0,0,1,2.9,9.251C2.127,7.708,1.953,7.468,2,7.293s0.05-.3.226-0.3,2.39,0,2.606,0a0.851,0.851,0,0,1,.351.326c0.075,0.1.647,1.056,0.822,1.356S7.046,10.38,7.513,10.38a0.6,0.6,0,0,0,.474-0.7c0-.4,0-1.979,0-2.18a1.94,1.94,0,0,0-.978-1A1.261,1.261,0,0,1,7.937,6c0.6-.025,2.1-0.025,2.43.024A0.779,0.779,0,0,1,10.994,6.771Z"></path>
                        </svg>
                    </li>

                    <li class="mobile-social-item">
                        <a href="" class="mobile-social-link" target="_blank" rel="nofollow" title="Twitter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="svg-inline-tw" width="20" height="20" viewBox="0 0 20 20">
                                <path class="cls-1" d="M10.294,8.784s0.2-2.739,2.175-2.763c1.61-.02,1.916.686,1.916,0.686A3.662,3.662,0,0,0,15.7,6a2.046,2.046,0,0,1-.539,1.234A1.365,1.365,0,0,0,16,6.942a1.6,1.6,0,0,1-.879,1.017A9.089,9.089,0,0,1,13.769,12.8c-1.291,2.11-4.055,2.171-5.49,2.188a7.855,7.855,0,0,1-3.272-.922A6.935,6.935,0,0,0,8.159,13.4,15.331,15.331,0,0,1,6,11.68,2.219,2.219,0,0,0,6.782,11.6,11.26,11.26,0,0,1,5.006,9.233a2.933,2.933,0,0,0,.819.041S4.557,7.281,5.156,5.989A8.159,8.159,0,0,0,10.294,8.784Z"></path>
                            </svg>
                            Twitter
                        </a>
                    </li>
                    <li class="mobile-social-item">
                        <a href="" class="mobile-social-link" target="_blank" rel="nofollow" title="Instagram">
                            <svg xmlns="http://www.w3.org/2000/svg" class="svg-inline-inst" width="20" height="20" viewBox="0 0 20 20">
                                <path class="cls-1" d="M13,17H7a4,4,0,0,1-4-4V7A4,4,0,0,1,7,3h6a4,4,0,0,1,4,4v6A4,4,0,0,1,13,17ZM15,7a2,2,0,0,0-2-2H7A2,2,0,0,0,5,7v6a2,2,0,0,0,2,2h6a2,2,0,0,0,2-2V7Zm-5,6a3,3,0,1,1,3-3A3,3,0,0,1,10,13Zm1-4H9v2h2V9Z"></path>
                            </svg>
                            Instagram
                        </a>
                    </li>
                    <li class="mobile-social-item">
                        <a href="" class="mobile-social-link" target="_blank" rel="nofollow" title="YouTube">
                            <svg xmlns="http://www.w3.org/2000/svg" class="svg-inline-yt" width="20" height="20" viewBox="0 0 20 20">
                                <path class="cls-1" d="M14,16H7a4,4,0,0,1-4-4V8A4,4,0,0,1,7,4h7a4,4,0,0,1,4,4v4A4,4,0,0,1,14,16Zm2-8a2,2,0,0,0-2-2H7A2,2,0,0,0,5,8v4a2,2,0,0,0,2,2h7a2,2,0,0,0,2-2V8ZM9,8l4,2L9,12V8Z"></path>
                            </svg>
                            YouTube
                        </a>
                    </li>
                    <li class="mobile-social-item">
                        <a href="" class="mobile-social-link" target="_blank" rel="nofollow" title="Одноклассники">
                            <svg xmlns="http://www.w3.org/2000/svg" class="svg-inline-ok" width="20" height="20" viewBox="0 0 20 20">
                                <path class="odcls-1" d="M10.5,10.99a3.5,3.5,0,1,1,3.518-3.5A3.508,3.508,0,0,1,10.5,10.99Zm0.008-5.034a1.519,1.519,0,1,0,1.535,1.518A1.528,1.528,0,0,0,10.505,5.956ZM7.491,11.079a6.074,6.074,0,0,1,1.119.542,4.382,4.382,0,0,0,1.887.395,5.828,5.828,0,0,0,1.964-.357,6,6,0,0,1,1.116-.565c0.221,0.008.583,0.373,0.521,1.318-0.035.538-1.018,0.761-1.363,0.968a2.284,2.284,0,0,1-.726.246s0.847,0.906,1.063,1.129a2.671,2.671,0,0,1,.948,1.278,1.342,1.342,0,0,1-1,1,4.485,4.485,0,0,1-1.4-1.12c-0.583-.557-1.115-1.069-1.115-1.069s-0.547.486-1.116,1.048a4.607,4.607,0,0,1-1.368,1.141,1.439,1.439,0,0,1-1.061-1.16A6.312,6.312,0,0,1,8.2,14.391,8.057,8.057,0,0,1,9,13.634a1.909,1.909,0,0,1-.638-0.208c-0.481-.267-1.511-0.547-1.484-1.043C6.9,11.87,7.035,11.079,7.491,11.079Z"></path>
                            </svg>
                            Одноклассники
                        </a>
                    </li>
                    <li class="mobile-social-item">
                        <a href="" class="mobile-social-link" target="_blank" rel="nofollow" title="Google Plus">
                            <svg xmlns="http://www.w3.org/2000/svg" class="svg-inline-gp" width="20" height="20" viewBox="0 0 20 20">
                                <path class="cls-1" d="M19,12H17v2H15V12H13V10h2V8h2v2h2v2ZM6.5,16a5.5,5.5,0,1,1,4.43-8.734l-1.7,1.066A3.495,3.495,0,1,0,9.65,12H7V10h5v2H11.767A5.483,5.483,0,0,1,6.5,16Z"></path>
                            </svg>
                            Google Plus
                        </a>
                    </li>
                    <li class="mobile-social-item">
                        <a href="" class="mobile-social-link" target="_blank" rel="nofollow" title="Mail.ru">
                            <svg xmlns="http://www.w3.org/2000/svg" class="svg-inline-ml" width="20" height="20" viewBox="0 0 20 20">
                                <path class="cls-1" d="M12.753,10.434a2.386,2.386,0,0,0-2.429-2.407H10.275A2.375,2.375,0,0,0,7.964,10.64a2.319,2.319,0,0,0,2.305,2.537,2.47,2.47,0,0,0,2.487-2.439Zm-2.47-3.752A3.649,3.649,0,0,1,12.9,7.861v0a0.555,0.555,0,0,1,.531-0.606H13.5a0.607,0.607,0,0,1,.581.628l0,5.367a0.334,0.334,0,0,0,.558.308c0.824-.886,1.81-4.552-0.512-6.677a5.368,5.368,0,0,0-6.612-.543,5.363,5.363,0,0,0-1.672,6.268A4.963,4.963,0,0,0,12.036,15.3c0.958-.4,1.4.95,0.406,1.393A6.49,6.49,0,0,1,4.8,13.749,6.581,6.581,0,0,1,15.394,6.092c2.226,2.432,2.1,6.987-.075,8.758A1.509,1.509,0,0,1,12.883,13.7l-0.01-.383a3.574,3.574,0,0,1-2.59,1.126,3.885,3.885,0,0,1,0-7.759h0"></path>
                            </svg>
                            Mail.ru
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="search" class="modal">
    <div class="close"></div>
    <form action="" method="get">
        <label>
            <input type="text" placeholder="Введите запрос и нажмите Enter" autofocus>
        </label>
        <button type="submit"></button>
    </form>
</div>

<div id="callback" class="modal">
    <div class="close"></div>
    <div class="title">
        <h2>Заказать звонок</h2>
    </div>
    <form action="" method="get">
        <label>
            Имя
            <input type="text" name="">
        </label>
        <label>
            Телефон
            <input type="text">
        </label>
        <label class="checkbox">
            <input type="checkbox">
            Я согласен на <a href="">обработку персональных данных</a> *
        </label>
        <input type="submit" value="Отправить">
    </form>
</div>

<div id="compare" class="modal">
    <div class="close"></div>
    <div class="title">
        <h2>Сравнение товаров</h2>
    </div>
    <div class="content">
        Здесь будет сравнение товаров
    </div>
</div>

<div id="favorites" class="modal">
    <div class="close"></div>
    <div class="title">
        <h2>Избранное</h2>
    </div>
    <div class="content">
        Здесь будут избранные товары
    </div>
</div>

<div id="auth" class="modal">
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
<!--        <label class="checkbox">-->
<!--            <input type="checkbox" name="personal_data" class="required">-->
<!--            Я согласен на <a href="">обработку персональных данных</a> <span class="red">*</span>-->
<!--            <span class="tooltip"></span>-->
<!--        </label>-->
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

<div id="location" class="modal cities">
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
                <? if (!empty($this->districts) && is_array($this->districts)):
                    foreach ($this->districts as $district): ?>
                        <li>
                            <a href="#" data-id="<?= $district->id ?>"><?= $district->name ?></a>
                        </li>
                    <? endforeach;
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

<script src="/js/jquery-3.4.1.min.js"></script>
<script src="/js/jquery.cookie.js"></script>
<script src="/js/ondelay.jquery.js"></script>
<script src="/js/jquery.autocomplete.min.js"></script>
<script src="/js/jquery.inputmask.js"></script>
<script src="/js/cities.js"></script>
<script src="/js/functions.js"></script>
<script src="/js/scripts.js"></script>
<script src="https://www.google.com/recaptcha/api.js"></script>
</html>
