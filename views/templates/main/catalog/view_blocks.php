<div class="product-container">
    <?php foreach ($this->items as $item): ?>
        <div class="product-item">
            <div class="product-item-stickers">
                <?php if (!empty($item['is_hit'])): ?>
                    <span class="stickers sticker-hit">Хит</span>
                <?php endif; ?>

                <?php if (!empty($item['is_new'])): ?>
                    <span class="stickers sticker-new">Новинка</span>
                <?php endif; ?>

                <?php if (!empty($item['is_action'])): ?>
                    <span class="stickers sticker-action">Акция</span>
                <?php endif; ?>

                <?php if (!empty($item['is_recommend'])): ?>
                    <span class="stickers sticker-recomend">Советуем</span>
                <?php endif; ?>

                <?php if (!empty($item['is_discount'])): ?>
                    <span class="stickers sticker-sale">Sale</span>
                    <span class="stickers sticker-discount"><?=$item['discount']?>%</span>
                <?php endif; ?>
            </div>

            <div class="product-item-like">
                <div class="like product-item-wish" data-id="<?= $item['id'] // TODO ?>"></div>
                <div class="like product-item-compare" data-id="<?= $item['id'] // TODO ?>"></div>
            </div>

            <div class="product-item-image">
                <a href="<?= $item['id'] ?>/"><img src="/uploads/catalog/<?= $item['id'] ?>/<?= $item['preview_image'] ?>" alt=""></a>
                <a href="<?= $item['id'] ?>/" class="product-item-fastview info"
                   data-id="<?= $item['id'] // TODO ?>" data-target="fast" data-url="/catalog/fastView/">
                    Быстрый просмотр
                </a>
            </div>
            <div class="product-item-title">
                <a href="<?= $item['id'] ?>/"><?= $item['name'] ?></a>
            </div>

            <div class="product-item-rating">
                <span class="star star-active"></span>
                <span class="star star-active"></span>
                <span class="star star-active"></span>
                <span class="star star-inactive"></span>
                <span class="star star-inactive"></span>
            </div>

            <div class="product-item-count">
                <span class="icon <?= (($item['quantity'] > 0) ? 'ok' : 'no') ?>"></span>
                <?= (($item['quantity'] > 10) ? 'Много' : (($item['quantity'] > 0) ? 'Мало' : 'Отсутствует')) ?>
                <div class="product-item-articul">Артикул: <?= $item['articul'] ?></div>
            </div>

            <?= $this->render("catalog/view_price_blocks", ['item' => $item]) ?>

            <div class="product-item-buy">
                <?php if ($item['quantity'] > 0): ?>
                    <span class="product-item-counter">
                        <span class="product-item-minus"></span>
                        <input type="text" name="quantity" value="1" max="<?= $item['quantity'] ?>" class="product-item-quantity">
                        <span class="product-item-plus"></span>
                    </span>
                    <span class="product-item-button buy" data-id="<?= $item['id'] // TODO ?>">В корзину</span>
                <?php else: ?>
                    <span class="product-item-absent" data-id="<?= $item['id'] // TODO ?>">Отложить</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
