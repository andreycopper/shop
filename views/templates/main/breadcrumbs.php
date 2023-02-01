<?php if (!empty($breadcrumbs) && is_array($breadcrumbs)): ?>
    <div class="breadcrumbs">
        <a href="/">Главная</a>
        <?php foreach ($breadcrumbs as $breadcrumb):
            $next = next($breadcrumbs);
            if ($next): ?>
                <a href="/<?= $breadcrumb['link'] ?>" class="breadcrumbs-link"><?= $breadcrumb['title'] ?></a>
            <?php else: ?>
                <span class="breadcrumbs-span"><?= $breadcrumb['title'] ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif;
