<?php if (!empty($item)): ?>
    <?php if (!empty(SHOW_ALL_PRICES_CATALOG) && !empty($item->prices) && is_array($item->prices)): ?>
        <?php foreach ($item->prices as $price): ?>
            <?php if (!in_array($price->price_type_id, $user->price_types)) continue; ?>
            <?php if (count($user->price_types) > 1 || !empty(SHOW_PRICE_TYPES)): ?>
                <div class="product-price-title"><?= $price->price_type ?></div>
            <?php endif; ?>

            <div class="product-itemtable-priceblock <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                <div class="product-itemtable-price">
                    <span class="product-itemtable-value">
                        <?= number_format(round($price->price * (100 - $price->discount) / 100), 0, '.', ' ') ?>
                    </span>
                    <span class="product-itemtable-currency"><?= $price->currency ?></span>
                    <span class="product-itemtable-measure">/<?= $item->unit ?></span>
                </div>

                <?php if (!empty($price->discount)): ?>
                    <div class="product-itemtable-oldprice <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                        <span class="product-itemtable-value"><?= number_format($price->price, 0, '.', ' ') ?></span>
                        <span class="product-itemtable-currency"><?= $price->currency ?></span>
                        <span class="product-itemtable-measure">/<?= $item->unit ?></span>
                        <div class="product-itemtable-priceline"></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php if (!empty(SHOW_PRICE_TYPES)): ?>
            <div class="product-price-title"><?= $item->price_type ?></div>
        <?php endif; ?>

        <div class="product-itemtable-priceblock">
            <div class="product-itemtable-price">
                <span class="product-itemtable-value">
                    <?= number_format(round($item->price * (100 - $item->discount) / 100), 0, '.', ' ') ?>
                </span>
                <span class="product-itemtable-currency"><?= $item->currency ?></span>
                <span class="product-itemtable-measure">/<?= $item->unit ?></span>
            </div>

            <?php if (!empty($item->discount)): ?>
                <div class="product-itemtable-oldprice">
                    <span class="product-itemtable-value"><?= number_format($item->price, 0, '.', ' ') ?></span>
                    <span class="product-itemtable-currency"><?= $item->currency ?></span>
                    <span class="product-itemtable-measure">/<?= $item->unit ?></span>
                    <div class="product-itemtable-priceline"></div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif;
