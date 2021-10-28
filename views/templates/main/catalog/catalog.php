<div class="catalog-container">
    <div class="catalog-leftmenu">
        <?= $this->render('side/menu_catalog') ?>
        <?= $this->render('side/marketing') ?>
        <?= $this->render('side/subscribe') ?>
        <?= $this->render('side/news') ?>
        <?= $this->render('side/articles') ?>
    </div>

    <div class="catalog-main">
        <div class="catalog-main-container">
            <?php if (!empty($groups) && is_array($groups)): ?>
                <?php foreach ($groups[0] as $group1): ?>
                    <div class="catalog-main-item">
                        <div class="catalog-main-top">
                            <div class="catalog-main-image">
                                <a href="/catalog/<?= $group1->link ?>/">
                                    <img src="/uploads/groups/<?= $group1->id ?>/<?= $group1->image ?>" alt="<?= $group1->name ?>">
                                </a>
                            </div>

                            <div class="catalog-main-header">
                                <div class="catalog-main-title"><a href="/catalog/<?= $group1->link ?>/"><?= $group1->name ?></a></div>

                                <?php if (!empty($groups[$group1->id]) && is_array($groups[$group1->id])): ?>
                                    <div class="catalog-main-list">
                                        <?php foreach ($groups[$group1->id] as $group2): ?>
                                            <a href="/catalog/<?= $group1->link ?>/<?= $group2->link ?>/">
                                                <?= $group2->name ?> <span><?= $group2->count ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="catalog-main-bottom"><?= $group1->description ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
