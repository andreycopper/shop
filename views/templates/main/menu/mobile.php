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

                <?php if (!empty($menu) && is_array($menu)): ?>
                    <?php foreach ($menu[0] as $menuItem): ?>
                        <li class="menu-mobile-item">
                            <a href="/<?=$menuItem->link?>/" class="menu-mobile-link <?=!empty($this->menu[$menuItem->id]) ? 'parent' : '';?>" title="<?=$menuItem->name?>">
                                <span><?=$menuItem->name?></span>
                            </a>
                            <?php if (!empty($menu[$menuItem->id])): ?>
                                <span class="menu-mobile-arrow"><i class="svg-triangle-right"></i></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>

                <li class="menu-mobile-item">
                    <a href="" class="menu-mobile-link menu-mobile-location parent mobile-action" rel="nofollow" data-target="location">
                        <span><?= $this->location->name ?? 'Москва' ?></span>
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
                            <span class="menu-mobile-count <?= intval($this->cartCount) > 0 ? '' : 'empty' ?>"><?= $this->cartCount ?></span>
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
