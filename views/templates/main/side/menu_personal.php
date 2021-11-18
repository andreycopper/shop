<?php if (!empty($menu_personal) && is_array($menu_personal)): ?>
    <div class="leftsubmenu">
        <?php foreach ($menu_personal as $menu_item): ?>
            <div class="leftsubmenu-item <?= $_SERVER['REQUEST_URI'] === ('/' . $menu_item->link . '/') ? 'current' : '' ?>">
                <a href="<?= '/' . $menu_item->link ?>/" class="leftsubmenu-title">
                    <?= $menu_item->name ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
