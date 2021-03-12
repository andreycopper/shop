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
                            <a href="/catalog/<?=$group1['link']?>/" class="catalog-leftmenu-link"><?=$group1['name']?></a>
                            <i class="fa fa-angle-right" aria-hidden="true"></i>

                            <? if (!empty($this->groups[$group1['id']]) && is_array($this->groups[$group1['id']])): ?>
                                <ul class="catalog-leftsubmenu">
                                    <? foreach ($this->groups[$group1['id']] as $group2): ?>
                                        <li class="catalog-leftsubmenu-item">
                                            <div class="catalog-leftsubmenu-image">
                                                <a href="/catalog/<?=$group1['link']?>/<?=$group2['link']?>/"><img src="/uploads/groups/<?=$group2['id']?>/<?=$group2['image']?>" alt=""></a>
                                            </div>

                                            <div class="catalog-leftsubmenu-main">
                                                <a href="/catalog/<?=$group1['link']?>/<?=$group2['link']?>/"><?=$group2['name']?></a>

                                                <? if (!empty($this->groups[$group2['id']]) && is_array($this->groups[$group2['id']])): ?>
                                                    <div class="catalog-leftsubmenu-subitems">
                                                        <? foreach ($this->groups[$group2['id']] as $group3): ?>
                                                            <a href="/catalog/<?=$group1['link']?>/<?=$group2['link']?>/<?=$group3['link']?>/"><?=$group3['name']?></a>
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

            <div class="catalog-left-filter">
                <div class="catalog-left-filter-header">
                    Фильтр по параметрам
                </div>

                <div class="catalog-left-filter-item">
                    <a href="" class="catalog-left-filter-title active">Розничная цена</a>
                    <div class="catalog-left-filter-body">
                        <div class="catalog-left-inputrange">
                            <label>
                                <input type="text" placeholder="1 300">
                            </label>
                            <span class="divider"></span>
                            <label>
                                <input type="text" placeholder="20 000">
                            </label>

                            <div class="catalog-left-filter-values">
                                <div class="catalog-left-filter-valueleft">1 300</div>
                                <div class="catalog-left-filter-valueright">22 000</div>
                            </div>

                            <div class="catalog-left-range">
                                <div class="catalog-left-range-line inactive"></div>
                                <div class="catalog-left-range-line active"></div>
                                <a href="" class="catalog-left-range-handle handle-left"></a>
                                <a href="" class="catalog-left-range-handle handle-right"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="catalog-left-filter-item">
                    <a href="" class="catalog-left-filter-title active">Наши предложения</a>
                    <div class="catalog-left-filter-body">
                        <div class="catalog-left-select">
                            <label>
                                <select name="" id="">
                                    <option value="">Все</option>
                                    <option value="">Хит</option>
                                    <option value="">Советуем</option>
                                    <option value="">Акция</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="catalog-left-filter-item">
                    <a href="" class="catalog-left-filter-title active">Бренды</a>
                    <div class="catalog-left-filter-body">
                        <div class="catalog-left-check">

                            <input id="brand-cobra" type="checkbox" name="brand-cobra">
                            <label for="brand-cobra">
                                <span class=""></span>Cobra
                            </label>

                            <input id="brand-garmin" type="checkbox" name="brand-garmin">
                            <label for="brand-garmin">
                                <span class=""></span>GARMIN
                            </label>

                            <input id="brand-intro" type="checkbox" name="brand-intro">
                            <label for="brand-intro">
                                <span class=""></span>Intro
                            </label>

                            <input id="brand-jvc" type="checkbox" name="brand-jvc">
                            <label for="brand-jvc">
                                <span class=""></span>JVC
                            </label>

                            <input id="brand-mystery" type="checkbox" name="brand-mystery">
                            <label for="brand-mystery">
                                <span class=""></span>Mystery
                            </label>

                            <input id="brand-parkcity" type="checkbox" name="brand-parkcity">
                            <label for="brand-parkcity">
                                <span class=""></span>Parkcity
                            </label>

                            <input id="brand-pioneer" type="checkbox" name="brand-pioneer">
                            <label for="brand-pioneer">
                                <span class=""></span>Pioneer
                            </label>

                            <input id="brand-ritmix" type="checkbox" name="brand-ritmix">
                            <label for="brand-ritmix">
                                <span class=""></span>RITMIX
                            </label>

                            <input id="brand-shome" type="checkbox" name="brand-shome">
                            <label for="brand-shome">
                                <span class=""></span>Sho me
                            </label>
                        </div>
                    </div>
                </div>

                <div class="catalog-left-filter-item">
                    <a href="" class="catalog-left-filter-title active">Тип</a>
                    <div class="catalog-left-filter-body">
                        <div class="catalog-left-radio">

                            <input id="type-all" type="radio" name="type">
                            <label for="type-all">
                                <span class="checked"></span>Все
                            </label>

                            <input id="type-1din" type="radio" name="type">
                            <label for="type-1din">
                                <span class=""></span>1 DIN
                            </label>

                        </div>
                    </div>
                </div>
            </div>

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

        <div class="catalog-main">
            <? if (!empty($this->subGroups) && is_array($this->subGroups)): ?>
                <div class="subcategories-container">
                    <? foreach ($this->subGroups as $subGroup): ?>
                        <div class="subcategories-item">
                            <a href="<?=$subGroup->link?>/">
                                <div class="subcategories-image">
                                    <img src="/uploads/groups/<?=$subGroup->id?>/<?=$subGroup->image?>" alt="">
                                </div>
                                <div class="subcategories-title"><?=$subGroup->name?></div>
                            </a>
                        </div>
                    <? endforeach; ?>
                </div>
            <? endif; ?>

            <div class="category-description">
                <?=($this->group->description ?? null)?>
            </div>

            <? if (!empty($this->items) && is_array($this->items)): ?>
                <div class="product-sort-view">
                    <div class="product-sort">
                        <div class="sort-product show
                            <?=(empty($_GET['order']) || $_GET['order'] === 'view' ? 'active' : '')?>
                            <?=((empty($_GET['order']) || $_GET['order'] === 'view') && !empty($_GET['sort']) && $_GET['sort'] === 'desc' ? 'desc' : '')?>">
                            <a href="?order=view<?=(((empty($_GET['order']) || $_GET['order'] === 'view') && empty($_GET['sort'])) ? '&sort=desc' : '')?>">По популярности</a>
                        </div>
                        <div class="sort-product name
                            <?=(!empty($_GET['order']) && $_GET['order'] === 'name' ? 'active' : '')?>
                            <?=(!empty($_GET['order']) && $_GET['order'] === 'name' && !empty($_GET['sort']) && $_GET['sort'] === 'desc' ? 'desc' : '')?>">
                            <a href="?order=name<?=((!empty($_GET['order']) && $_GET['order'] === 'name' && empty($_GET['sort'])) ? '&sort=desc' : '')?>">По алфавиту</a>
                        </div>
                        <div class="sort-product price
                            <?=(!empty($_GET['order']) && $_GET['order'] === 'price' ? 'active' : '')?>
                            <?=(!empty($_GET['order']) && $_GET['order'] === 'price' && !empty($_GET['sort']) && $_GET['sort'] === 'desc' ? 'desc' : '')?>">
                            <a href="?order=price<?=((!empty($_GET['order']) && $_GET['order'] === 'price' && empty($_GET['sort'])) ? '&sort=desc' : '')?>">По цене</a>
                        </div>
                    </div>

                    <div class="product-view">
                        <a href="" data-view="block" title="плитки"
                           class="view-product block <?=((empty($_COOKIE['view']) || $_COOKIE['view'] === 'block') ? 'active' : '')?>"><i></i></a>
                        <a href="" data-view="list" title="список"
                           class="view-product list <?=((!empty($_COOKIE['view']) && $_COOKIE['view'] === 'list') ? 'active' : '')?>"><i></i></a>
                        <a href="" data-view="table" title="таблица"
                           class="view-product table <?=((!empty($_COOKIE['view']) && $_COOKIE['view'] === 'table') ? 'active' : '')?>"><i></i></a>
                    </div>
                </div>

                <? require __DIR__ . '/catalog_' . ($_COOKIE['view'] ?? 'block') . '.php'; ?>
            <? else: ?>
                <div class="product-container">
                    <p class="required">Товары не найдены</p>
                </div>
            <? endif; ?>

            <? include __DIR__ . '/../pagination.php'; ?>
        </div>
    </div>
</div>
