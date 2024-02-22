<?php
use Entity\Product;

/**
 * @var Product $item
 */

if ($item->detailTextType === 'text'): ?><pre><?php endif; ?>

<?= $item->detailText ?>

<?php if ($item->detailTextType === 'text'): ?></pre><?php endif; ?>
