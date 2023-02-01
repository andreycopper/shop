<?php if (!empty($item)): ?>
    <?php if (!empty(SHOW_ALL_PRICES_CATALOG) && !empty($item->prices) && is_array($item->prices)): ?>
        <?php foreach ($item->prices as $price): ?>
            <?php if (!in_array($price->price_type_id, $user->price_types)) continue; ?>
            <?php if (count($user->price_types) > 1 || !empty(SHOW_PRICE_TYPES)): ?>
                <div class="product-price-title"><?= $price->price_type ?></div>
            <?php endif; ?>

            <?php if (!empty($price->discount)): ?>
                <div class="product-item-oldprice <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                    <span class="product-item-value"><?= number_format($price->price, 0, '.', ' ') ?></span>
                    <span class="product-item-currency"><?= $price->currency ?></span><span class="product-item-measure">/<?= $item->unit ?></span>
                    <div class="product-item-priceline"></div>
                </div>
            <?php endif; ?>

            <div class="product-itemlist-price  <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                <span class="product-itemlist-value">
                    <?= number_format(round($price->price * (100 - $price->discount) / 100), 0, '.', ' ') ?>
                </span>
                <span class="product-itemlist-currency"><?= $price->currency ?></span>
                <span class="product-itemlist-measure">/<?= $item->unit ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php if (!empty(SHOW_PRICE_TYPES)): ?>
            <div class="product-price-title"><?= $item->price_type ?></div>
        <?php endif; ?>

        <?php if (!empty($item->discount)): ?>
            <div class="product-item-oldprice">
                <span class="product-item-value"><?= number_format($item->price, 0, '.', ' ') ?></span>
                <span class="product-item-currency"><?= $item->currency ?></span><span class="product-item-measure">/<?= $item->unit ?></span>
                <div class="product-item-priceline"></div>
            </div>
        <?php endif; ?>

        <div class="product-itemlist-price">
            <span class="product-itemlist-value">
                <?= number_format(round($item->price * (100 - $item->discount) / 100), 0, '.', ' ') ?>
            </span>
            <span class="product-itemlist-currency"><?= $item->currency ?></span>
            <span class="product-itemlist-measure">/<?= $item->unit ?></span>
        </div>
    <?php endif; ?>
<?php endif;

