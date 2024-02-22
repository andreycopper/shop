<div class="catalog-container">
    <div class="leftmenu">
        <?= $this->render('menu/personal') ?>
        <?= $this->render('side/marketing') ?>
        <?= $this->render('side/subscribe') ?>
        <?= $this->render('side/news') ?>
        <?= $this->render('side/articles') ?>
    </div>

    <div class="main-section">
        <div class="personal-container">
            <?php if (!empty($menu_personal) && is_array($menu_personal)): ?>
                <?php foreach ($menu_personal as $menu_item): ?>
                    <a href="<?= '/' . $menu_item->link ?>/" class="personal-item <?= $menu_item->link ?>">
                        <?= $menu_item->name ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
