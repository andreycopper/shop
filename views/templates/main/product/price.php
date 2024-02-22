<?php
use Entity\User;
use Entity\Product;
use Entity\ProductPrice;

/**
 * @var User $user
 * @var Product $item
 * @var ProductPrice $price
 */

if (defined(SHOW_ALL_PRICES_CATALOG) && !empty($item->prices) && is_array($item->prices)): ?>
    <?php foreach ($item->prices as $price): ?>
        <?php if (!in_array($price->priceTypeId, $user->priceTypes)) continue; ?>

        <?php $price_discount = round($price->price * (100 - $price->discount) / 100); ?>

        <div class="product-info-price">
            <?php if (count($item->prices) > 1 || defined(SHOW_PRICE_TYPES)): ?>
                <div class="product-info-price-title"><?= $price->priceType ?></div>
            <?php endif; ?>

            <?php if (!empty($price->discount)): ?>
                <div class="product-oldprice <?= $price->priceTypeId !== $user->priceTypeId ? 'inactive' : '' ?>">
                    <span class="product-value">
                        <?= number_format($price->price, 0, '.', ' ') ?>
                    </span>

                    <span class="product-currency"><?= $price->currency ?></span>
                    <span class="product-measure">/<?= $item->unit ?></span>

                    <div class="product-priceline"></div>
                </div>
            <?php endif; ?>

            <div class="product-price <?= $price->priceTypeId !== $user->priceTypeId ? 'inactive' : '' ?>">
                <span class="product-value">
                    <?= number_format($price_discount, 0, '.', ' ') ?>
                </span>

                <span class="product-currency"><?= $price->currency ?></span>
                <span class="product-measure">/<?= $item->unit ?></span>

                <?php if (!empty($item->taxValue)): ?>
                    <span class="product-nds">
                        (в т.ч. <?= $item->tax ?>
                        <?= number_format(round($price_discount * $item->taxValue / (100 + $item->taxValue), 2), 2, '.', ' ') ?>
                        <?= $price->currency ?>)
                        </span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <?php $price_discount = round($item->price * (100 - $item->discount) / 100); ?>

    <div class="product-info-price">
        <?php if (defined(SHOW_PRICE_TYPES)): ?>
            <div class="product-info-price-title"><?= $item->priceType ?></div>
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

            <?php if (!empty($item->taxValue)): ?>
                <span class="product-nds">
                    (в т.ч. <?= $item->tax ?>
                    <?= number_format(round($price_discount * $item->taxValue / (100 + $item->taxValue), 2), 2, '.', ' ') ?>
                    <?= $item->currency ?>)
                </span>
            <?php endif; ?>
        </div>
    </div>
<?php endif;
