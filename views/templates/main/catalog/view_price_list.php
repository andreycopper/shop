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

            <?php if (!empty($price['discount'])): ?>
                <div class="product-item-oldprice <?= $price['price_type_id'] !== $user->priceTypeId ? 'inactive' : '' ?>">
                    <span class="product-item-value"><?= number_format(round($price['price']), 0, '.', ' ') ?></span>

                    <span class="product-item-currency"><?= $price['currency_sign'] ?></span>
                    <span class="product-item-measure">/<?= $item['unit_sign'] ?></span>

                    <div class="product-item-priceline"></div>
                </div>
            <?php endif; ?>

            <div class="product-itemlist-price  <?= $price['price_type_id'] !== $user->priceTypeId ? 'inactive' : '' ?>">
                <span class="product-itemlist-value">
                    <?= number_format(round($price['price'] * (100 - $price['discount']) / 100), 0, '.', ' ') ?>
                </span>

                <span class="product-itemlist-currency"><?= $price['currency_sign'] ?></span>
                <span class="product-itemlist-measure">/<?= $item['unit_sign'] ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php if (defined(SHOW_PRICE_TYPES)): ?>
            <div class="product-price-title"><?= $item['price_type'] ?></div>
        <?php endif; ?>

        <?php if (!empty($item['discount'])): ?>
            <div class="product-item-oldprice">
                <span class="product-item-value"><?= number_format(round($item['price']), 0, '.', ' ') ?></span>

                <span class="product-item-currency"><?= $item['currency_sign'] ?></span>
                <span class="product-item-measure">/<?= $item['unit_sign'] ?></span>

                <div class="product-item-priceline"></div>
            </div>
        <?php endif; ?>

        <div class="product-itemlist-price">
            <span class="product-itemlist-value">
                <?= number_format(round($item['price'] * (100 - $item['discount']) / 100), 0, '.', ' ') ?>
            </span>

            <span class="product-itemlist-currency"><?= $item['currency_sign'] ?></span>
            <span class="product-itemlist-measure">/<?= $item['unit_sign'] ?></span>
        </div>
    <?php endif; ?>
<?php endif;
