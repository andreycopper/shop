<?php if (!empty($menuPersonal) && is_array($menuPersonal)): ?>
    <div class="leftsubmenu">
        <?php foreach ($menuPersonal as $menuItem): ?>
            <div class="leftsubmenu-item <?= $_SERVER['REQUEST_URI'] === "/{$menuItem['link']}/" ? 'current' : '' ?>">
                <a href="<?= "/{$menuItem['link']}/" ?>" class="leftsubmenu-title">
                    <?= $menuItem['name'] ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif;
