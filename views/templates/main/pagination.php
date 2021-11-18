<?php
use System\Request;

if (!empty($total_pages) && $total_pages > 1): ?>
    <?php
        $get = Request::get() ?: [];
        if (!empty($get['page'])) unset($get['page']);
        $url = http_build_query($get);
    ?>
    <div class="load">
        <span>Показать еще</span>
    </div>

    <div class="pagination">
        <?php if ($page_current > 1): ?>
            <?php if ($page_current > 2): ?>
                <a href="?<?= $url ?>" class="start"></a>
            <?php endif; ?>

            <a href="?<?= $url . ($url ? '&' : '') ?>page=<?=($page_current - 1)?>" class="prev"></a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i < $page_current - 2 || $i > $page_current + 2) continue; ?>

            <?php if ($i === $page_current): ?>
                <span><?=$i?></span>
            <?php else: ?>
                <a href="?<?= $url . ($url ? '&' : '') ?>page=<?=$i?>"><?=$i?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page_current < $total_pages): ?>
            <a href="?<?= $url . ($url ? '&' : '') ?>page=<?=($page_current + 1)?>" class="next"></a>

            <?php if ($page_current < $total_pages - 1): ?>
                <a href="?<?= $url . ($url ? '&' : '') ?>page=<?=$total_pages?>" class="end"></a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
