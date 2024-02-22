<ul class="catalog-leftmenu-list">
    <?php if (!empty($menuCatalog[0]) && is_array($menuCatalog[0])): ?>
        <?php foreach ($menuCatalog[0] as $group1): ?>
            <?php
            $current =
                !empty(ROUTE[1]) && mb_stripos($group1['link'], str_replace('_', '-', ROUTE[1])) !== false ?
                    'current' :
                    '';
            ?>

            <li class="catalog-leftmenu-item <?= $current ?>">
                <a href="/catalog/<?= $group1['link'] ?>/" class="catalog-leftmenu-link"><?= $group1['name'] ?></a>
                <i class="fa fa-angle-right" aria-hidden="true"></i>

                <?php if (!empty($menuCatalog[$group1['id']]) && is_array($menuCatalog[$group1['id']])): ?>
                    <ul class="catalog-leftsubmenu">
                        <?php foreach ($menuCatalog[$group1['id']] as $group2): ?>
                            <li class="catalog-leftsubmenu-item">
                                <div class="catalog-leftsubmenu-image">
                                    <a href="/catalog/<?= $group1['link'] ?>/<?= $group2['link'] ?>/">
                                        <img src="/uploads/groups/<?= $group2['id'] ?>/<?= $group2['image'] ?>" alt="">
                                    </a>
                                </div>

                                <div class="catalog-leftsubmenu-main">
                                    <a href="/catalog/<?= $group1['link'] ?>/<?= $group2['link'] ?>/"><?= $group2['name'] ?></a>

                                    <?php if (!empty($menuCatalog[$group2['id']]) && is_array($menuCatalog[$group2['id']])): ?>
                                        <div class="catalog-leftsubmenu-subitems">
                                            <?php foreach ($menuCatalog[$group2['id']] as $group3): ?>
                                                <a href="/catalog/<?= $group1['link'] ?>/<?= $group2['link'] ?>/<?= $group3['link'] ?>/"><?= $group3['name'] ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>
