<div class="catalog-container">
    <div class="leftmenu">
        <?= $this->render('side/menu_catalog') ?>
        <?= $this->render('side/marketing') ?>
        <?= $this->render('side/subscribe') ?>
        <?= $this->render('side/news') ?>
        <?= $this->render('side/articles') ?>
    </div>

    <div class="main-section">
        <div class="product-main">
            <div class="product-photo">
                <div class="product-photo-main">
                    <a href="/uploads/catalog/<?= $item->id ?>/<?= $item->detail_image ?>" data-lightbox="<?= $item->name ?>" data-title="<?= $item->name ?>">
                        <img src="/uploads/catalog/<?= $item->id ?>/<?= $item->detail_image ?>" alt="">
                    </a>
                    <div class="product-item-stickers">
                        <?php if (!empty($item->hit)): ?>
                            <span class="stickers sticker-hit">Хит</span>
                        <?php endif; ?>
                        <?php if (!empty($item->new)): ?>
                            <span class="stickers sticker-new">Новинка</span>
                        <?php endif; ?>
                        <?php if (!empty($item->action)): ?>
                            <span class="stickers sticker-action">Акция</span>
                        <?php endif; ?>
                        <?php if (!empty($item->recommend)): ?>
                            <span class="stickers sticker-recomend">Советуем</span>
                        <?php endif; ?>
                        <?php if (!empty($item->discount)): ?>
                            <span class="stickers sticker-sale">Sale</span>
                            <span class="stickers sticker-discount"><?= $item->discount ?>%</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-item-like">
                        <div class="like product-item-wish" data-id="<?= $item->id ?>" title="В избранное"></div>
                        <div class="like product-item-compare" data-id="<?= $item->id ?>" title="Сравнить"></div>
                    </div>
                    <div class="product-view"></div>
                </div>
                <div class="product-slide">
                    <?php if (!empty($item->images) && is_array($item->images)):
                        foreach ($item->images as $image): ?>
                            <div class="product-slide-item left">
                                <a href="/uploads/catalog/<?= $item->id ?>/<?= $image->image ?>" data-lightbox="<?= $item->name ?>" data-title="<?= $item->name ?>">
                                    <img src="/uploads/catalog/<?= $item->id ?>/<?= $image->image ?>" alt="">
                                </a>
                            </div>
                        <?php endforeach;
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
                    <?php if (!empty($item->vendor_image)): ?>
                        <div class="product-vendor">
                            <a href="/vendors/<?=mb_strtolower($item->vendor)?>">
                                <img src="/uploads/vendor/<?= $item->vendor_image ?>" alt="">
                            </a>
                        </div>
                    <?php endif; ?>
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

                <?php if (!empty($item->prices) && is_array($item->prices)): ?>
                    <?php foreach ($item->prices as $price): ?>
                        <?php $price_discount = round($price->price * (100 - $price->discount) / 100); ?>

                        <div class="product-info-price">
                            <?php if (count($item->prices) > 1): ?>
                                <div class="product-info-price-title"><?= $price->price_type ?></div>
                            <?php endif; ?>
                            <?php if (!empty($price->discount)): ?>
                                <div class="product-oldprice <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                                    <span class="product-value">
                                        <?= number_format($price->price, 0, '.', ' ') ?>
                                    </span>
                                    <span class="product-currency"><?=$price->currency?></span>
                                    <span class="product-measure">/<?=$item->unit?></span>
                                    <div class="product-priceline"></div>
                                </div>
                            <?php endif; ?>
                            <div class="product-price <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                                <span class="product-value">
                                    <?= number_format($price_discount, 0, '.', ' ') ?>
                                </span>
                                <span class="product-currency"><?= $price->currency ?></span>
                                <span class="product-measure">/<?= $item->unit ?></span>
                                <?php if (!empty($item->tax_value)): ?>
                                    <span class="product-nds">
                                        (в т.ч. <?= $item->tax_name ?>
                                        <?= number_format(round($price_discount * $item->tax_value / (100 + $item->tax_value), 2), 2, '.', ' ') ?>
                                        <?= $price->currency ?>)
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="product-info-count">
                    <span class="icon <?= (($item->quantity > 0) ? 'ok' : 'no') ?>"></span>
                    <?= (($item->quantity > 10) ? 'Много' : (($item->quantity > 0) ? 'Мало' : 'Отсутствует')) ?>
                </div>
                <div class="product-info-buy">
                    <?php if ($item->quantity > 0): ?>
                         <span class="product-counter">
                            <span class="product-minus"></span>
                            <input type="text" name="quantity" value="1" max="<?=$item->quantity?>" class="product-quantity">
                            <span class="product-plus"></span>
                        </span>
                        <span class="product-button buy" data-id="<?=$item->id?>">В корзину</span>
                        <a class="product-altbutton order-action" data-target="qorder">Быстрый заказ</a>
                    <?php else: ?>
                        <span class="product-altbutton" data-id="<?=$item->id?>">Отложить</span>
                    <?php endif; ?>
                </div>
                <div class="product-info-message">
                    <p>Цена действительна только для интернет-магазина и может отличаться от цен в розничных магазинах</p>
                </div>
            </div>
        </div>

        <?= $this->render('product/help') ?>

        <div class="product-description">
            <div class="product-tabs">
                <div class="product-tab active" data-target="desc">Описание</div>
                <div class="product-tab" data-target="props">Характеристики</div>
                <div class="product-tab" data-target="videos">Видео (1)</div>
                <div class="product-tab" data-target="reviews">Отзывы (2)</div>
                <div class="product-tab" data-target="quantities">Наличие</div>
            </div>
            <div id="desc" class="product-content active">
                <?= $this->render('product/desc') ?>
            </div>
            <div id="props" class="product-content">
                <?= $this->render('product/props') ?>
            </div>
            <div id="videos" class="product-content">
                <?= $this->render('product/videos') ?>
            </div>
            <div id="reviews" class="product-content">
                <?= $this->render('product/reviews') ?>
            </div>
            <div id="quantities" class="product-content">
                <?= $this->render('product/quantities') ?>
            </div>
        </div>

        <?= $this->render('product/other') ?>
    </div>
</div>

<?= $this->render('product/views') ?>
