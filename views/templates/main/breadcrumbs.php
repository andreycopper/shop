<?php if (!empty($breadcrumbs) && is_array($breadcrumbs)): ?>
    <div class="breadcrumbs">
        <a href="/">Главная</a>
        <?php foreach ($breadcrumbs as $breadcrumb): ?>
            <?php if (!empty($breadcrumb['link'])): ?>
                <a href="<?= $breadcrumb['link'] ?>" class="breadcrumbs-link"><?= $breadcrumb['name'] ?></a>
            <?php else: ?>
                <span class="breadcrumbs-span"><?= $breadcrumb['name'] ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif;
