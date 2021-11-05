<div class="product-container">
    <? foreach ($this->items as $item): ?>
        <div class="product-itemlist">
            <div class="product-itemlist-image">
                <a href="<?=$item->id?>/"><img src="/uploads/catalog/<?=$item->id?>/<?=$item->preview_image?>" alt=""></a>
                <a class="product-itemlist-fastview info" data-target="fast" data-url="/catalog/fastView/">Быстрый просмотр</a>
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
                    <?= ($item->preview_text ?? mb_substr(strip_tags($item->detail_text), 0, mb_strpos(strip_tags($item->detail_text), '.', 150) + 1)) ?>
                </div>

                <div class="product-itemlist-moreprops"><a href="" class="">Характеристики</a></div>
                <div class="product-itemlist-properties">
                    <div class="product-itemlist-prop">
                        <div class="product-itemlist-propname"><span>Бренд</span></div>
                        <div class="product-itemlist-propvalue"><span><?=$item->vendor?></span></div>
                    </div>
                    <div class="product-itemlist-prop">
                        <div class="product-itemlist-propname"><span>Гарантия</span></div>
                        <div class="product-itemlist-propvalue"><span><?=(!empty($item->warranty) ? $item->warranty_name : 'Без гарантии')?>  </span></div>
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
                <?php if (!empty($item->prices) && is_array($item->prices)): ?>
                    <?php foreach ($item->prices as $price): ?>
                        <?php if (count($item->prices) > 1): ?>
                            <div class="product-price-title"><?= $price->price_type ?></div>
                        <?php endif; ?>

                        <? if (!empty($price->discount)): ?>
                            <div class="product-item-oldprice <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                                <span class="product-item-value"><?= number_format($price->price, 0, '.', ' ') ?></span>
                                <span class="product-item-currency"><?= $price->currency ?></span><span class="product-item-measure">/<?= $item->unit ?></span>
                                <div class="product-item-priceline"></div>
                            </div>
                        <? endif; ?>

                        <div class="product-itemlist-price  <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                            <span class="product-itemlist-value">
                                <?= number_format(round($price->price * (100 - $price->discount) / 100), 0, '.', ' ') ?>
                            </span>
                            <span class="product-itemlist-currency"><?= $price->currency ?></span>
                            <span class="product-itemlist-measure">/<?= $item->unit ?></span>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>

                <? if ($item->quantity > 0): ?>
                    <div class="product-itemlist-counter">
                        <span class="product-itemlist-minus"></span>
                        <input type="text" name="quantity" value="1" max="<?= $item->quantity ?>" class="product-itemlist-quantity">
                        <span class="product-itemlist-plus"></span>
                    </div>
                    <span class="product-itemlist-button buy" data-id="<?= $item->id ?>">В корзину</span>
                <? else: ?>
                    <span class="product-item-absent" data-id="<?= $item->id ?>">Отложить</span>
                <? endif; ?>
            </div>
        </div>
    <? endforeach; ?>
</div>

<script>
    $(function () {
        /* раскрытие характеристик товара в каталоге при отображении списком */
        $('.product-itemlist-moreprops a').on('click', function (e){
            e.preventDefault();
            $(this).toggleClass('active').parents('.product-itemlist-moreprops').next().slideToggle();

        });
    });
</script>
