<div class="product-container">
    <?php foreach ($this->items as $item): ?>
        <div class="product-itemlist">
            <div class="product-itemlist-image">
                <a href="<?= $item['id'] ?>/"><img src="/uploads/catalog/<?= $item['id'] ?>/<?= $item['preview_image'] ?>" alt=""></a>

                <a class="product-itemlist-fastview info" data-target="fast" data-url="/catalog/fastView/">Быстрый просмотр</a><?php // TODO ?>

                <div class="product-item-stickers">
                    <?php if (!empty($item['hit'])): ?><span class="stickers sticker-hit">Хит</span><?php endif; ?>

                    <?php if (!empty($item['new'])): ?><span class="stickers sticker-new">Новинка</span><?php endif; ?>

                    <?php if (!empty($item['action'])): ?><span class="stickers sticker-action">Акция</span><?php endif; ?>

                    <?php if (!empty($item['recommend'])): ?><span class="stickers sticker-recomend">Советуем</span><?php endif; ?>

                    <?php if (!empty($item['discount'])): ?>
                        <span class="stickers sticker-sale">Sale</span>
                        <span class="stickers sticker-discount"><?= $item['discount'] ?>%</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="product-itemlist-description">
                <div class="product-item-title"><a href="<?= $item['id'] ?>/"><?= $item['name'] ?></a></div>

                <div class="product-itemlist-rating">
                    <span class="star star-active"></span>
                    <span class="star star-active"></span>
                    <span class="star star-active"></span>
                    <span class="star star-inactive"></span>
                    <span class="star star-inactive"></span>
                </div>

                <div class="product-itemlist-count">
                    <span class="icon <?= $item['quantity'] > 0 ? 'ok' : 'no' ?>"></span>

                    <?= $item['quantity'] > 10 ? 'Много' : (($item['quantity'] > 0) ? 'Мало' : 'Отсутствует') ?>

                    <span class="product-itemlist-articul">Артикул: <?= $item['articul'] ?></span>
                </div>

                <div class="product-itemlist-text">
                    <?= $item['preview_text'] ?:
                            mb_substr(
                                    strip_tags($item['detail_text']),
                                    0,
                                    mb_strpos(
                                            strip_tags($item['detail_text']),
                                            '.',
                                        mb_strlen($item['detail_text']) > 150 ? 150 : mb_strlen($item['detail_text'])
                                    ) + 1
                            )
                    ?>
                </div>

                <div class="product-itemlist-moreprops"><a href="" class="">Характеристики</a></div>

                <div class="product-itemlist-properties">
                    <div class="product-itemlist-prop">
                        <div class="product-itemlist-propname"><span>Бренд</span></div>
                        <div class="product-itemlist-propvalue"><span><?= $item['vendor'] ?></span></div>
                    </div>

                    <div class="product-itemlist-prop">
                        <div class="product-itemlist-propname"><span>Гарантия</span></div>
                        <div class="product-itemlist-propvalue"><span><?= $item['warranty'] ?>  </span></div>
                    </div>

                    <div class="product-itemlist-prop">
                        <div class="product-itemlist-propname"><span>Вес, кг</span></div>
                        <div class="product-itemlist-propvalue"><span>1.5 кг</span></div>
                    </div>
                </div>

                <div class="product-itemlist-like">
                    <a href="" class="product-itemlist-wish">Отложить</a><?php // TODO ?>
                    <a href="" class="product-itemlist-compare">Сравнить</a><?php // TODO ?>
                </div>
            </div>

            <div class="product-itemlist-buy">
                <?= $this->render("catalog/view_price_list", ['item' => $item]) ?>

                <?php if ($item['quantity'] > 0): ?>
                    <div class="product-itemlist-counter">
                        <span class="product-itemlist-minus"></span>

                        <input type="text" name="quantity" value="1" max="<?= $item['quantity'] ?>" class="product-itemlist-quantity">

                        <span class="product-itemlist-plus"></span>
                    </div>
                    <span class="product-itemlist-button buy" data-id="<?= $item['id'] ?>">В корзину</span>
                <?php else: ?>
                    <span class="product-item-absent" data-id="<?= $item['id'] ?>">Отложить</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
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
