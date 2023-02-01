<?php if (!empty(SHOW_ALL_PRICES_CATALOG) && !empty($item->prices) && is_array($item->prices)): ?>
    <?php foreach ($item->prices as $price): ?>
        <?php if (!in_array($price->price_type_id, $user->price_types)) continue; ?>
        <?php $price_discount = round($price->price * (100 - $price->discount) / 100); ?>

        <div class="product-info-price">
            <?php if (count($item->prices) > 1 || !empty(SHOW_PRICE_TYPES)): ?>
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
<?php else: ?>
    <?php $price_discount = round($item->price * (100 - $item->discount) / 100); ?>

    <div class="product-info-price">
        <?php if (!empty(SHOW_PRICE_TYPES)): ?>
            <div class="product-info-price-title"><?= $item->price_type ?></div>
        <?php endif; ?>
        <?php if (!empty($item->discount)): ?>
            <div class="product-oldprice">
                <span class="product-value">
                    <?= number_format($item->price, 0, '.', ' ') ?>
                </span>
                <span class="product-currency"><?=$item->currency?></span>
                <span class="product-measure">/<?=$item->unit?></span>
                <div class="product-priceline"></div>
            </div>
        <?php endif; ?>
        <div class="product-price">
            <span class="product-value">
                <?= number_format($price_discount, 0, '.', ' ') ?>
            </span>
            <span class="product-currency"><?= $item->currency ?></span>
            <span class="product-measure">/<?= $item->unit ?></span>
            <?php if (!empty($item->tax_value)): ?>
                <span class="product-nds">
                    (в т.ч. <?= $item->tax_name ?>
                    <?= number_format(round($price_discount * $item->tax_value / (100 + $item->tax_value), 2), 2, '.', ' ') ?>
                    <?= $item->currency ?>)
                </span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

