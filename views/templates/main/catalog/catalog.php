<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator">-</span><span>Каталог</span>
        </div>

        <h1>Каталог</h1>
    </div>

    <div class="catalog-container">
        <div class="catalog-leftmenu">
            <ul class="catalog-leftmenu-list">
                <? if (!empty($this->groups)): ?>
                    <? foreach ($this->groups[0] as $group1): ?>
                        <li class="catalog-leftmenu-item">
                            <a href="/catalog/<?=$group1['title']?>/" class="catalog-leftmenu-link"><?=$group1['title']?></a>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>

                            <? if (!empty($this->groups[$group1['id']]) && is_array($this->groups[$group1['id']])): ?>
                                <ul class="catalog-leftsubmenu">
                                    <? foreach ($this->groups[$group1['id']] as $group2): ?>
                                        <li class="catalog-leftsubmenu-item">
                                            <div class="catalog-leftsubmenu-image">
                                                <a href="/catalog/<?=$group1['title']?>/<?=$group2['title']?>/"><img src="/uploads/groups/<?=$group2['id']?>/<?=$group2['image']?>" alt=""></a>
                                            </div>

                                            <div class="catalog-leftsubmenu-main">
                                                <a href="/catalog/<?=$group1['title']?>/<?=$group2['title']?>/"><?=$group2['title']?></a>

                                                <? if (!empty($this->groups[$group2['id']]) && is_array($this->groups[$group2['id']])): ?>
                                                    <div class="catalog-leftsubmenu-subitems">
                                                        <? foreach ($this->groups[$group2['id']] as $group3): ?>
                                                            <a href="/catalog/<?=$group1['title']?>/<?=$group2['title']?>/<?=$group3['title']?>/"><?=$group3['title']?></a>
                                                        <? endforeach; ?>
                                                    </div>
                                                <? endif; ?>
                                            </div>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                            <? endif; ?>
                        </li>
                    <? endforeach; ?>
                <? endif; ?>
            </ul>

            <div class="sidemenu-picture">
                <a href="">
                    <img src="/uploads/catalog/leftside/1.jpg" alt="">
                </a>
                <div class="sidemenu-picture-desc">
                    <div class="sidemenu-picture-title"></div>
                    <div class="sidemenu-picture-text"></div>
                </div>
            </div>

            <div class="sidemenu-picture">
                <a href="">
                    <img src="/uploads/catalog/leftside/2.jpg" alt="">
                </a>
                <div class="sidemenu-picture-desc">
                    <div class="sidemenu-picture-title"></div>
                    <div class="sidemenu-picture-text"></div>
                </div>
            </div>

            <div class="sidemenu-subscribe">
                <div class="sidemenu-subscribe-container">
                    <div class="sidemenu-subscribe-title">
                        Будь всегда в курсе!
                    </div>
                    <div class="sidemenu-subscribe-text">
                        Узнавайте о скидках и акциях первым
                    </div>
                    <form action="" class="sidemenu-subscribe-form">
                        <label>
                            <input type="text" placeholder="Ваш e-mail">
                        </label>
                        <input type="submit" value="">
                    </form>
                </div>
            </div>

            <div class="sidemenu-news">
                <div class="sidemenu-news-header">
                    <div class="sidemenu-news-header-title">Новости</div>
                    <div class="sidemenu-news-header-link"><a href="">Все новости</a></div>
                </div>

                <div class="sidemenu-news-item">
                    <div class="sidemenu-news-date">22 июня 2019</div>
                    <a href="" class="sidemenu-news-title">Триумфальное возвращение Nokia 3310</a>
                </div>

                <div class="sidemenu-news-item">
                    <div class="sidemenu-news-date">5 июня 2019</div>
                    <a href="" class="sidemenu-news-title">Новое восприятие мира от Google</a>
                </div>

                <div class="sidemenu-news-item">
                    <div class="sidemenu-news-date">1 июня 2019</div>
                    <a href="" class="sidemenu-news-title">Переводчик с кошачьего – это реально</a>
                </div>
            </div>

            <div class="sidemenu-news">
                <div class="sidemenu-news-header">
                    <div class="sidemenu-news-header-title">Статьи</div>
                    <div class="sidemenu-news-header-link"><a href="">Все статьи</a></div>
                </div>

                <div class="sidemenu-news-item">
                    <a href="" class="sidemenu-news-title">Обзор 10 лучших велосипедных брендов</a>
                </div>

                <div class="sidemenu-news-item">
                    <a href="" class="sidemenu-news-title">5 ожидаемых техноновинок, которые изменят мир</a>
                </div>

                <div class="sidemenu-news-item">
                    <a href="" class="sidemenu-news-title">Фантастические изобретения, которые уже стали реальностью</a>
                </div>
            </div>
        </div>
        <!--            -->
        <div class="catalog-main">
            <div class="catalog-main-container">
                <? if (!empty($this->groups)): ?>
                    <? foreach ($this->groups[0] as $group1): ?>
                        <div class="catalog-main-item">
                            <div class="catalog-main-top">
                                <div class="catalog-main-image">
                                    <a href="/catalog/<?=$group1['title']?>/"><img src="/uploads/groups/<?=$group1['id']?>/<?=$group1['image']?>" alt="<?=$group1['title']?>"></a>
                                </div>

                                <div class="catalog-main-header">
                                    <div class="catalog-main-title"><a href="/catalog/<?=$group1['title']?>/"><?=$group1['title']?></a></div>

                                    <? if (!empty($this->groups[$group1['id']]) && is_array($this->groups[$group1['id']])): ?>
                                        <div class="catalog-main-list">
                                            <? foreach ($this->groups[$group1['id']] as $group2): ?>
                                                <a href="/catalog/<?=$group1['title']?>/<?=$group2['title']?>/"><?=$group2['title']?> <span>6</span></a>
                                            <? endforeach; ?>
                                        </div>
                                    <? endif; ?>
                                </div>
                            </div>

                            <div class="catalog-main-bottom">
                                <?=$group1['description']?>
                            </div>
                        </div>
                    <? endforeach; ?>
                <? endif; ?>
            </div>
        </div>
    </div>
</div>
