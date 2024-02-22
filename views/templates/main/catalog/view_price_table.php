<?php
use Entity\User;

/**
 * @var User $user
 * @var array $item
 */

if (!empty($item)): ?>
    <?php if (defined(SHOW_ALL_PRICES_CATALOG) && !empty($item['prices']) && is_array($item['prices'])): ?>
        <?php foreach ($item['prices'] as $price): ?>
            <?php if (!in_array($price['price_type_id'], $user->priceTypes)) continue; ?>

            <?php if (count($user->priceTypes) > 1 || defined(SHOW_PRICE_TYPES)): ?>
                <div class="product-price-title"><?= $price['price_type'] ?></div>
            <?php endif; ?>

            <div class="product-itemtable-priceblock <?= $price['price_type_id'] !== $user->priceTypeId ? 'inactive' : '' ?>">
                <div class="product-itemtable-price">
                    <span class="product-itemtable-value">
                        <?= number_format(round($price['price'] * (100 - $price['discount']) / 100), 0, '.', ' ') ?>
                    </span>

                    <span class="product-itemtable-currency"><?= $price['currency'] ?></span>
                    <span class="product-itemtable-measure">/<?= $item['unit_sign'] ?></span>
                </div>

                <?php if (!empty($price->discount)): ?>
                    <div class="product-itemtable-oldprice <?= $price['price_type_id'] !== $user->priceTypeId ? 'inactive' : '' ?>">
                        <span class="product-itemtable-value"><?= number_format(round($price['price']), 0, '.', ' ') ?></span>

                        <span class="product-itemtable-currency"><?= $price['currency_sign'] ?></span>
                        <span class="product-itemtable-measure">/<?= $item['unit_sign'] ?></span>

                        <div class="product-itemtable-priceline"></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php if (defined(SHOW_PRICE_TYPES)): ?>
            <div class="product-price-title"><?= $item['price_type'] ?></div>
        <?php endif; ?>

        <div class="product-itemtable-priceblock">
            <div class="product-itemtable-price">
                <span class="product-itemtable-value">
                    <?= number_format(round($item['price'] * (100 - $item['discount']) / 100), 0, '.', ' ') ?>
                </span>

                <span class="product-itemtable-currency"><?= $item['currency_sign'] ?></span>
                <span class="product-itemtable-measure">/<?= $item['unit_sign'] ?></span>
            </div>

            <?php if (!empty($item['discount'])): ?>
                <div class="product-itemtable-oldprice">
                    <span class="product-itemtable-value"><?= number_format(round($item['price']), 0, '.', ' ') ?></span>

                    <span class="product-itemtable-currency"><?= $item['currency_sign'] ?></span>
                    <span class="product-itemtable-measure">/<?= $item['unit_sign'] ?></span>

                    <div class="product-itemtable-priceline"></div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif;
