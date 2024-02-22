<?php
use Entity\Product;

/**
 * @var Product $item
 */

if (!empty($item->stores)): ?>
    <?php foreach ($item->stores as $store): ?>
        <?php if (!empty($store->quantity)): ?>
            <div class="product-quantity-item relative">
                <div class="product-quantity-name"><?= $store->address ?></div>
                <div class="product-quantity-count"><?= $store->quantity ?></div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif;
