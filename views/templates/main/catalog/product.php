<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator">-</span>
            <a href="">Бытовая техника</a><span class="breadcrumbs-separator">-</span>
            <a href="">Микроволновые печи</a><span class="breadcrumbs-separator">-</span>
            <span><?=$item->name?></span>
        </div>

        <h1><?=$item->name?></h1>
    </div>

    <div class="catalog-container">
        <div class="leftmenu">
            <div class="leftsubmenu">
                <div class="leftsubmenu-item">
                    <a href="" class="leftsubmenu-title">Сотрудники</a>
                </div>

                <div class="leftsubmenu-item">
                    <a href="" class="leftsubmenu-title">Вакансии</a>
                </div>

                <div class="leftsubmenu-item">
                    <a href="" class="leftsubmenu-title">Лицензии</a>
                </div>

                <div class="leftsubmenu-item current">
                    <a href="" class="leftsubmenu-title">Политика</a>
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

        <div class="main-section">
            <div class="product-main">
                <div class="product-photo">
                    <div class="product-photo-main">
                        <a href="/uploads/catalog/<?= $item->id ?>/<?= $item->detail_image ?>" data-lightbox="<?= $item->name ?>" data-title="<?= $item->name ?>">
                            <img src="/uploads/catalog/<?= $item->id ?>/<?= $item->detail_image ?>" alt="">
                        </a>
                        <div class="product-item-stickers">
                            <? if (!empty($item->hit)): ?>
                                <span class="stickers sticker-hit">Хит</span>
                            <? endif; ?>
                            <? if (!empty($item->new)): ?>
                                <span class="stickers sticker-new">Новинка</span>
                            <? endif; ?>
                            <? if (!empty($item->action)): ?>
                                <span class="stickers sticker-action">Акция</span>
                            <? endif; ?>
                            <? if (!empty($item->recommend)): ?>
                                <span class="stickers sticker-recomend">Советуем</span>
                            <? endif; ?>
                            <? if (!empty($item->discount)): ?>
                                <span class="stickers sticker-sale">Sale</span>
                                <span class="stickers sticker-discount"><?= $item->discount ?>%</span>
                            <? endif; ?>
                        </div>
                        <div class="product-item-like">
                            <div class="like product-item-wish" data-id="<?= $item->id ?>" title="В избранное"></div>
                            <div class="like product-item-compare" data-id="<?= $item->id ?>" title="Сравнить"></div>
                        </div>
                        <div class="product-view"></div>
                    </div>
                    <div class="product-slide">
                        <? if (!empty($item->images) && is_array($item->images)):
                            foreach ($item->images as $image): ?>
                                <div class="product-slide-item left">
                                    <a href="/uploads/catalog/<?= $item->id ?>/<?= $image->image ?>" data-lightbox="<?= $item->name ?>" data-title="<?= $item->name ?>">
                                        <img src="/uploads/catalog/<?= $item->id ?>/<?= $image->image ?>" alt="">
                                    </a>
                                </div>
                            <? endforeach;
                        endif; ?>
                    </div>
                </div>
                <div class="product-info">
                    <div class="product-info-tech">
                        <div class="product-rating">
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-inactive"></span>
                            <span class="star star-inactive"></span>
                        </div>
                        <div class="product-articul">Артикул: <?= $item->articul ?></div>
                        <? if (!empty($item->vendor_image)): ?>
                            <div class="product-vendor">
                                <a href="/vendors/<?=mb_strtolower($item->vendor)?>">
                                    <img src="/uploads/vendor/<?= $item->vendor_image ?>" alt="">
                                </a>
                            </div>
                        <? endif; ?>
                    </div>
                    <div class="product-info-text">
                        <?=($item->preview_text ?
                            (('text' === $item->detail_text_type) ?
                                ('<pre>' . $item->preview_text . '</pre>') :
                                $item->preview_text) :
                            mb_substr(strip_tags($item->detail_text), 0, mb_strpos(strip_tags($item->detail_text), '.', 100) + 1))?>
                        <div class="product-info-more relative">
                            <a href="" class="product-more">Подробнее</a>
                        </div>
                    </div>
                    <? if (!empty($item->prices) && is_array($item->prices)): ?>
                        <? foreach ($item->prices as $price): ?>

                            <div class="product-info-price">
                                <? if (count($item->prices) > 1): ?>
                                    <div class="product-info-price-title"><?= $price->price_type ?></div>
                                <? endif; ?>
                                <? if (!empty($item->discount)): ?>
                                    <div class="product-oldprice">
                                        <span class="product-value">
                                            <?=number_format($price->price, 0, '.', ' ')?>
                                        </span>
                                        <span class="product-currency"><?=$price->currency?></span>
                                        <span class="product-measure">/<?=$item->unit?></span>
                                        <div class="product-priceline"></div>
                                    </div>
                                <? endif; ?>
                                <div class="product-price">
                                    <span class="product-value">
                                        <?=number_format(($price->price * (100 - $item->discount) / 100), 0, '.', ' ')?>
                                    </span>
                                    <span class="product-currency"><?=$price->currency?></span>
                                    <span class="product-measure">/<?=$item->unit?></span>
                                    <? if (!empty($item->tax_value)): ?>
                                        <span class="product-nds">
                                            (в т.ч. <?= $item->tax_name ?>
                                            <?= number_format(round($price->price * $item->tax_value / (100 + $item->tax_value), 2), 2, '.', ' ') ?>
                                            <?=$price->currency?>)
                                        </span>
                                    <? endif; ?>
                                </div>
                            </div>

                        <? endforeach; ?>
                    <? endif; ?>
                    <div class="product-info-count">
                        <span class="icon <?=(($item->quantity > 0) ? 'ok' : 'no')?>"></span>
                        <?=(($item->quantity > 10) ? 'Много' : (($item->quantity > 0) ? 'Мало' : 'Отсутствует'))?>
                    </div>
                    <div class="product-info-buy">
                        <? if ($item->quantity > 0): ?>
                             <span class="product-counter">
                                <span class="product-minus"></span>
                                <input type="text" name="quantity" value="1" max="<?=$item->quantity?>" class="product-quantity">
                                <span class="product-plus"></span>
                            </span>
                            <span class="product-button buy" data-id="<?=$item->id?>">В корзину</span>
                            <a class="product-altbutton order-action" data-target="qorder">Быстрый заказ</a>
                        <? else: ?>
                            <span class="product-altbutton" data-id="<?=$item->id?>">Отложить</span>
                        <? endif; ?>
                    </div>
                    <div class="product-info-message">
                        <p>Цена действительна только для интернет-магазина и может отличаться от цен в розничных магазинах</p>
                    </div>
                </div>
            </div>
            <div class="product-help">
                <div class="product-help-item">
                    <a href="/actions/"><img src="/images/gift.png" alt=""></a>
                    <a href="/actions/">Подарочные сертификаты</a>
                </div>
                <div class="product-help-item">
                    <a href="/company/licenses/"><img src="/images/sert.png" alt=""></a>
                    <a href="/company/licenses/">Весь товар сертифицирован</a>
                </div>
                <div class="product-help-item">
                    <a href="/help/return/"><img src="/images/return.png" alt=""></a>
                    <a href="/help/return/">Возврат и обмен товара</a>
                </div>
                <div class="product-help-item">
                    <a href="/help/delivery/"><img src="/images/delivery.png" alt=""></a>
                    <a href="/help/delivery/">Удобная и быстрая доставка</a>
                </div>
            </div>

            <div class="product-description">
                <div class="product-tabs">
                    <div class="product-tab active" data-target="desc">Описание</div>
                    <div class="product-tab" data-target="props">Характеристики</div>
                    <div class="product-tab" data-target="reviews">Отзывы (2)</div>
                </div>
                <div id="desc" class="product-content active">
                    <? if ('text' === $item->detail_text_type): ?><pre><? endif; ?><?=$item->detail_text?><? if ('text' === $item->detail_text_type): ?></pre><? endif; ?>
                </div>
                <div id="props" class="product-content">
                    <table class="dotted-list">
                        <tr>
                            <td><span>Гарантия</span></td>
                            <td><span>1 год</span></td>
                        </tr>
                        <tr>
                            <td><span>Режимы</td>
                            <td><span>5</span></td>
                        </tr>
                        <tr>
                            <td><span>Вес, кг</td>
                            <td><span>2.652</span></td>
                        </tr>
                        <tr>
                            <td><span>Тип управления</td>
                            <td><span>ручное</span></td>
                        </tr>
                        <tr>
                            <td><span>Объем, л</td>
                            <td><span>32</span></td>
                        </tr>
                        <tr>
                            <td><span>Гриль</td>
                            <td><span>тэновый</span></td>
                        </tr>
                    </table>
                </div>
                <div id="reviews" class="product-content">
                    <div class="product-review">
                        <div class="product-review-header">
                            <div class="product-review-name">Тестовое Имя</div>
                            <div class="product-review-email">test@mail.ru</div>
                            <div class="product-review-date">09.07.2020</div>
                        </div>
                        <div class="product-review-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad, amet beatae cum deleniti eaque eos fuga ipsam maiores
                            nemo odit porro rem? Accusamus exercitationem repellendus sed ut velit! Aliquid animi blanditiis dolor enim error
                            est eum id, illum itaque iure minima molestiae officia officiis, porro quo temporibus unde vitae voluptatem?
                        </div>
                    </div>
                    <div class="product-review">
                        <div class="product-review-header">
                            <div class="product-review-name">Тестовое Имя</div>
                            <div class="product-review-email">test@mail.ru</div>
                            <div class="product-review-date">09.07.2020</div>
                        </div>
                        <div class="product-review-text">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad, amet beatae cum deleniti eaque eos fuga ipsam maiores
                            nemo odit porro rem? Accusamus exercitationem repellendus sed ut velit! Aliquid animi blanditiis dolor enim error
                        </div>
                    </div>

                    <h5>Оставить отзыв:</h5>
                    <form action="" class="product-review-form">
                        <div class="product-review-contact">
                            <label>
                                <input type="text" name="name" placeholder="Имя:">
                            </label>
                            <label>
                                <input type="text" name="email" placeholder="Email:">
                            </label>
                        </div>
                        <div class="product-review-message">
                            <label>
                                <textarea name="message" placeholder="Сообщение:"></textarea>
                            </label>
                        </div>
                        <div class="product-review-button">
                            <input type="reset" class="btn-alt" value="Очистить">
                            <input type="submit" name="send" class="btn" value="Отправить">
                        </div>
                    </form>
                </div>
            </div>

            <div class="product-other">
                <h5>С этим товаром покупают:</h5>
                <div class="product-other-container">
                    <div class="product-other-item">
                        <div class="product-other-stickers">
                            <span class="stickers sticker-hit">Хит</span>
                            <span class="stickers sticker-new">Новинка</span>
                            <span class="stickers sticker-action">Акция</span>
                            <span class="stickers sticker-recomend">Советуем</span>
                            <span class="stickers sticker-sale">Sale</span>
                            <span class="stickers sticker-discount">10%</span>
                        </div>
                        <div class="product-other-like">
                            <div class="like product-other-wish"></div>
                            <div class="like product-other-compare"></div>
                        </div>
                        <div class="product-other-image">
                            <a href=""><img src="/uploads/catalog/2/1.jpg" alt=""></a>
                            <div class="product-other-fastview">Быстрый просмотр</div>
                        </div>
                        <div class="product-other-title">
                            <a href="">Микроволновая печь BEKO MOC20W Lorem ipsum dolor.</a>
                        </div>
                        <div class="product-other-rating">
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-inactive"></span>
                            <span class="star star-inactive"></span>
                        </div>
                        <div class="product-other-count"><span class="icon ok"></span>Много</div>
                        <div class="product-other-oldprice">
                            <span class="product-other-value">4 000</span> <span class="product-other-currency">руб</span><span class="product-other-measure">/шт</span>
                            <div class="product-item-priceline"></div>
                        </div>
                        <div class="product-other-price">
                            <span class="product-other-value">3 500</span> <span class="product-other-currency">руб</span><span class="product-other-measure">/шт</span>
                        </div>
                        <div class="product-other-buy">
                            <a href="" class="btn">Подробнее</a>
                        </div>
                    </div>
                    <div class="product-other-item">
                        <div class="product-other-stickers">
                            <span class="stickers sticker-hit">Хит</span>
                            <span class="stickers sticker-new">Новинка</span>
                            <span class="stickers sticker-action">Акция</span>
                            <span class="stickers sticker-recomend">Советуем</span>
                            <span class="stickers sticker-sale">Sale</span>
                            <span class="stickers sticker-discount">10%</span>
                        </div>
                        <div class="product-other-like">
                            <div class="like product-other-wish"></div>
                            <div class="like product-other-compare"></div>
                        </div>
                        <div class="product-other-image">
                            <a href=""><img src="/uploads/catalog/3/3.jpg" alt=""></a>
                            <div class="product-other-fastview">Быстрый просмотр</div>
                        </div>
                        <div class="product-other-title">
                            <a href="">Микроволновая печь BEKO MOC20W</a>
                        </div>
                        <div class="product-other-rating">
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-inactive"></span>
                            <span class="star star-inactive"></span>
                        </div>
                        <div class="product-other-count"><span class="icon ok"></span>Много</div>
                        <div class="product-other-oldprice">
                            <span class="product-other-value">4 000</span> <span class="product-other-currency">руб</span><span class="product-other-measure">/шт</span>
                            <div class="product-other-priceline"></div>
                        </div>
                        <div class="product-other-price">
                            <span class="product-other-value">3 500</span> <span class="product-other-currency">руб</span><span class="product-other-measure">/шт</span>
                        </div>
                        <div class="product-other-buy">
                            <a href="" class="btn">Подробнее</a>
                        </div>
                    </div>
                    <div class="product-other-item">
                        <div class="product-other-stickers">
                            <span class="stickers sticker-hit">Хит</span>
                            <span class="stickers sticker-new">Новинка</span>
                            <span class="stickers sticker-action">Акция</span>
                            <span class="stickers sticker-recomend">Советуем</span>
                            <span class="stickers sticker-sale">Sale</span>
                            <span class="stickers sticker-discount">10%</span>
                        </div>
                        <div class="product-other-like">
                            <div class="like product-other-wish"></div>
                            <div class="like product-other-compare"></div>
                        </div>
                        <div class="product-other-image">
                            <a href=""><img src="/uploads/catalog/4/1.jpg" alt=""></a>
                            <div class="product-other-fastview">Быстрый просмотр</div>
                        </div>
                        <div class="product-other-title">
                            <a href="">Микроволновая печь BEKO MOC20W</a>
                        </div>
                        <div class="product-other-rating">
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-inactive"></span>
                            <span class="star star-inactive"></span>
                        </div>
                        <div class="product-other-count"><span class="icon ok"></span>Много</div>
                        <div class="product-other-oldprice">
                            <span class="product-other-value">4 000</span> <span class="product-other-currency">руб</span><span class="product-other-measure">/шт</span>
                            <div class="product-other-priceline"></div>
                        </div>
                        <div class="product-other-price">
                            <span class="product-other-value">3 500</span> <span class="product-other-currency">руб</span><span class="product-other-measure">/шт</span>
                        </div>
                        <div class="product-other-buy">
                            <a href="" class="btn">Подробнее</a>
                        </div>
                    </div>
                    <div class="product-other-item">
                        <div class="product-other-stickers">
                            <span class="stickers sticker-hit">Хит</span>
                            <span class="stickers sticker-new">Новинка</span>
                            <span class="stickers sticker-action">Акция</span>
                            <span class="stickers sticker-recomend">Советуем</span>
                            <span class="stickers sticker-sale">Sale</span>
                            <span class="stickers sticker-discount">10%</span>
                        </div>
                        <div class="product-other-like">
                            <div class="like product-other-wish"></div>
                            <div class="like product-other-compare"></div>
                        </div>
                        <div class="product-other-image">
                            <a href=""><img src="/uploads/catalog/5/1.jpg" alt=""></a>
                            <div class="product-other-fastview">Быстрый просмотр</div>
                        </div>
                        <div class="product-other-title">
                            <a href="">Микроволновая печь BEKO MOC20W</a>
                        </div>
                        <div class="product-other-rating">
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-active"></span>
                            <span class="star star-inactive"></span>
                            <span class="star star-inactive"></span>
                        </div>
                        <div class="product-other-count"><span class="icon ok"></span>Много</div>
                        <div class="product-other-oldprice">
                            <span class="product-other-value">4 000</span> <span class="product-other-currency">руб</span><span class="product-other-measure">/шт</span>
                            <div class="product-other-priceline"></div>
                        </div>
                        <div class="product-other-price">
                            <span class="product-other-value">3 500</span> <span class="product-other-currency">руб</span><span class="product-other-measure">/шт</span>
                        </div>
                        <div class="product-other-buy">
                            <a href="" class="btn">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-views">
    <div class="container">
        <h5>Вы недавно смотрели:</h5>
        <div class="product-views-container">
            <div class="product-views-item">
                <div class="product-views-image">
                    <a href=""><img src="/uploads/catalog/1/1.jpeg" alt=""></a>
                </div>
                <div class="product-views-text">
                    <div class="product-views-title"><a href="">Микроволновая печь BEKO MOC20W</a></div>
                    <div class="product-views-price">3 500 руб/шт</div>
                </div>
            </div>
            <div class="product-views-item">
                <div class="product-views-image">
                    <a href=""><img src="/uploads/catalog/1/1.jpeg" alt=""></a>
                </div>
                <div class="product-views-text">
                    <div class="product-views-title"><a href="">Микроволновая печь BEKO MOC20W</a></div>
                    <div class="product-views-price">3 500 руб/шт</div>
                </div>
            </div>
            <div class="product-views-item">
                <div class="product-views-image">
                    <a href=""><img src="/uploads/catalog/1/1.jpeg" alt=""></a>
                </div>
                <div class="product-views-text">
                    <div class="product-views-title"><a href="">Микроволновая печь BEKO MOC20W</a></div>
                    <div class="product-views-price">3 500 руб/шт</div>
                </div>
            </div>
            <div class="product-views-item">
                <div class="product-views-image">
                    <a href=""><img src="/uploads/catalog/1/1.jpeg" alt=""></a>
                </div>
                <div class="product-views-text">
                    <div class="product-views-title"><a href="">Микроволновая печь BEKO MOC20W</a></div>
                    <div class="product-views-price">3 500 руб/шт</div>
                </div>
            </div>
            <div class="product-views-item">
                <div class="product-views-image">
                    <a href=""><img src="/uploads/catalog/1/1.jpeg" alt=""></a>
                </div>
                <div class="product-views-text">
                    <div class="product-views-title"><a href="">Микроволновая печь BEKO MOC20W</a></div>
                    <div class="product-views-price">3 500 руб/шт</div>
                </div>
            </div>

        </div>
    </div>
</div>
