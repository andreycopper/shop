<?php
use System\Request;

/**
 * @var int $currentPage
 * @var int $totalItems
 * @var int $elementsPerPage
 */

$totalPages = ceil($totalItems / $elementsPerPage);

if (!empty($totalPages) && $totalPages > 1): ?>
    <?php
        $get = Request::get() ?: [];
        if (!empty($get['page'])) unset($get['page']);
        $url = http_build_query($get);
    ?>
    <div class="load"><span>Показать еще</span></div>

    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <?php if ($currentPage > 2): ?>
                <a href="?<?= $url ?>" class="start"></a>
            <?php endif; ?>

            <a href="?<?= $url . ($url ? '&' : '') ?>page=<?= $currentPage - 1 ?>" class="prev"></a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i < $currentPage - 2 || $i > $currentPage + 2) continue; ?>

            <?php if ($i === $currentPage): ?>
                <span><?= $i ?></span>
            <?php else: ?>
                <a href="?<?= $url . ($url ? '&' : '') ?>page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?<?= $url . ($url ? '&' : '') ?>page=<?= $currentPage + 1 ?>" class="next"></a>

            <?php if ($currentPage < $totalPages - 1): ?>
                <a href="?<?= $url . ($url ? '&' : '') ?>page=<?= $totalPages ?>" class="end"></a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif;
