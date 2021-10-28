<div class="product-container">
<!--    --><?// foreach ($this->items as $item): ?>
<!--        <div class="product-item">-->
<!--            <div class="product-item-stickers">-->
<!--                --><?// if (!empty($item->hit)): ?>
<!--                    <span class="stickers sticker-hit">Хит</span>-->
<!--                --><?// endif; ?>
<!--                --><?// if (!empty($item->new)): ?>
<!--                    <span class="stickers sticker-new">Новинка</span>-->
<!--                --><?// endif; ?>
<!--                --><?// if (!empty($item->action)): ?>
<!--                    <span class="stickers sticker-action">Акция</span>-->
<!--                --><?// endif; ?>
<!--                --><?// if (!empty($item->recommend)): ?>
<!--                    <span class="stickers sticker-recomend">Советуем</span>-->
<!--                --><?// endif; ?>
<!--                --><?// if (!empty($item->discount)): ?>
<!--                    <span class="stickers sticker-sale">Sale</span>-->
<!--                    <span class="stickers sticker-discount">--><?//=$item->discount?><!--%</span>-->
<!--                --><?// endif; ?>
<!--            </div>-->
<!--            <div class="product-item-like">-->
<!--                <div class="like product-item-wish"></div>-->
<!--                <div class="like product-item-compare"></div>-->
<!--            </div>-->
<!--            <div class="product-item-image">-->
<!--                <a href="--><?//=$item->id?><!--/"><img src="/uploads/catalog/--><?//=$item->id?><!--/--><?//=$item->preview_image?><!--" alt=""></a>-->
<!--                <a href="--><?//=$item->id?><!--/" class="product-item-fastview">Быстрый просмотр</a>-->
<!--            </div>-->
<!--            <div class="product-item-title">-->
<!--                <a href="--><?//=$item->id?><!--/">--><?//=$item->name?><!--</a>-->
<!--            </div>-->
<!--            <div class="product-item-rating">-->
<!--                <span class="star star-active"></span>-->
<!--                <span class="star star-active"></span>-->
<!--                <span class="star star-active"></span>-->
<!--                <span class="star star-inactive"></span>-->
<!--                <span class="star star-inactive"></span>-->
<!--            </div>-->
<!--            <div class="product-item-count">-->
<!--                <span class="icon --><?//=(($item->quantity > 0) ? 'ok' : 'no')?><!--"></span>-->
<!--                --><?//=(($item->quantity > 10) ? 'Много' : (($item->quantity > 0) ? 'Мало' : 'Отсутствует'))?>
<!--                <div class="product-item-articul">Артикул: --><?//=$item->articul?><!--</div>-->
<!--            </div>-->
<!--            --><?// if (!empty($item->discount)): ?>
<!--                <div class="product-item-oldprice">-->
<!--                    <span class="product-item-value">--><?//=number_format($item->price, 0, '.', ' ')?><!--</span>-->
<!--                    <span class="product-item-currency">--><?//=$item->currency?><!--</span><span class="product-item-measure">/--><?//=$item->unit?><!--</span>-->
<!--                    <div class="product-item-priceline"></div>-->
<!--                </div>-->
<!--            --><?// endif; ?>
<!--            <div class="product-item-price">-->
<!--                <span class="product-item-value">-->
<!--                    --><?//=number_format(($item->price * (100 - $item->discount) / 100), 0, '.', ' ')?>
<!--                </span>-->
<!--                <span class="product-item-currency">--><?//=$item->currency?><!--</span>-->
<!--                <span class="product-item-measure">/--><?//=$item->unit?><!--</span>-->
<!--            </div>-->
<!--            <div class="product-item-buy">-->
<!--                --><?// if ($item->quantity > 0): ?>
<!--                    <span class="product-item-counter">-->
<!--                        <span class="product-item-minus"></span>-->
<!--                        <input type="text" name="quantity" value="1" max="--><?//=$item->quantity?><!--" class="product-item-quantity">-->
<!--                        <span class="product-item-plus"></span>-->
<!--                    </span>-->
<!--                    <span class="product-item-button">В корзину</span>-->
<!--                --><?// else: ?>
<!--                    <span class="product-item-absent">Отложить</span>-->
<!--                --><?// endif; ?>
<!--            </div>-->
<!--        </div>-->
<!--    --><?// endforeach; ?>

    <? foreach ($this->items as $item): ?>
        <div class="product-itemlist">
            <div class="product-itemlist-image">
                <a href="<?=$item->id?>/"><img src="/uploads/catalog/<?=$item->id?>/<?=$item->preview_image?>" alt=""></a>
                <div class="product-itemlist-fastview">Быстрый просмотр</div>
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
                        <span class="stickers sticker-discount"><?=$item->discount?>%</span>
                    <? endif; ?>
                </div>
            </div>

            <div class="product-itemlist-description">
                <div class="product-item-title">
                    <a href="<?=$item->id?>/"><?=$item->name?></a>
                </div>

                <div class="product-itemlist-rating">
                    <span class="star star-active"></span>
                    <span class="star star-active"></span>
                    <span class="star star-active"></span>
                    <span class="star star-inactive"></span>
                    <span class="star star-inactive"></span>
                </div>

                <div class="product-itemlist-count">
                    <span class="icon <?=(($item->quantity > 0) ? 'ok' : 'no')?>"></span>
                    <?=(($item->quantity > 10) ? 'Много' : (($item->quantity > 0) ? 'Мало' : 'Отсутствует'))?>
                    <span class="product-itemlist-articul">Артикул: <?=$item->articul?></span>
                </div>

                <div class="product-itemlist-text">
                    <?=($item->preview_text ?? mb_substr(strip_tags($item->detail_text), 0, mb_strpos(strip_tags($item->detail_text), '.', 300) + 1))?>
                </div>

                <div class="product-itemlist-moreprops"><a href="" class="">Характеристики</a></div>
                <div class="product-itemlist-properties">
                    <div class="product-itemlist-prop">
                        <div class="product-itemlist-propname"><span>Бренд</span></div>
                        <div class="product-itemlist-propvalue"><span><?=$item->vendor?></span></div>
                    </div>
                    <div class="product-itemlist-prop">
                        <div class="product-itemlist-propname"><span>Гарантия</span></div>
                        <div class="product-itemlist-propvalue"><span><?=(!empty($item->warranty) ? $item->warranty . ' ' . $item->warranty_period : 'Без гарантии')?>  </span></div>
                    </div>
                    <div class="product-itemlist-prop">
                        <div class="product-itemlist-propname"><span>Вес, кг</span></div>
                        <div class="product-itemlist-propvalue"><span>1.5 кг</span></div>
                    </div>
                </div>

                <div class="product-itemlist-like">
                    <a href="" class="product-itemlist-wish">Отложить</a>
                    <a href="" class="product-itemlist-compare">Сравнить</a>
                </div>
            </div>

            <div class="product-itemlist-buy">
                <? if (!empty($item->discount)): ?>
                    <div class="product-item-oldprice">
                        <span class="product-item-value"><?=number_format($item->price, 0, '.', ' ')?></span>
                        <span class="product-item-currency"><?=$item->currency?></span><span class="product-item-measure">/<?=$item->unit?></span>
                        <div class="product-item-priceline"></div>
                    </div>
                <? endif; ?>

                <div class="product-itemlist-price">
                    <span class="product-itemlist-value">
                        <?=number_format(($item->price * (100 - $item->discount) / 100), 0, '.', ' ')?>
                    </span>
                    <span class="product-itemlist-currency"><?=$item->currency?></span>
                    <span class="product-itemlist-measure">/<?=$item->unit?></span>
                </div>

                <? if ($item->quantity > 0): ?>
                    <div class="product-itemlist-counter">
                        <span class="product-itemlist-minus"></span>
                        <input type="text" name="quantity" value="1" max="<?=$item->quantity?>" class="product-itemlist-quantity">
                        <span class="product-itemlist-plus"></span>
                    </div>
                    <span class="product-itemlist-button buy" data-id="<?=$item->id?>" data-price-type-id="<?=$item->price_type_id?>>В корзину</span>
                <? else: ?>
                    <span class="product-item-absent">Отложить</span>
                <? endif; ?>
            </div>
        </div>
    <? endforeach; ?>
</div>
