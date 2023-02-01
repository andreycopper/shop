<div class="nav-item nav-catalog-dropdown">
    <a href="/catalog/" class="nav-item-link nav-catalog">
        Каталог
        <span class="nav-item-bottom"></span>
    </a>
    <div class="nav-catalog-menu">
        <ul class="nav-catalog-container">
            <?php if (!empty($groups) && is_array($groups)): ?>
                <?php foreach ($groups[0] as $group1): ?>
                    <li>
                        <a href="/catalog/<?= $group1->link ?>/" class="nav-catalog-image">
                            <img src="/uploads/groups/<?= $group1->id ?>/<?=$group1->image?>" alt="">
                        </a>
                        <a href="/catalog/<?=$group1->link?>/" class="nav-catalog-title"><?=$group1->name?></a>

                        <?php if (!empty($this->groups[$group1->id])): ?>
                            <ul class="nav-catalog-submenu">
                                <?php foreach ($this->groups[$group1->id] as $group2): ?>
                                    <li>
                                        <a href="/catalog/<?=$group1->link?>/<?=$group2->link?>/"><?=$group2->name?></a>

                                        <?php if (!empty($this->groups[$group2->id])): ?>
                                            <ul class="nav-catalog-submenu">
                                                <?php foreach ($this->groups[$group2->id] as $group3): ?>
                                                    <li><a href="/catalog/<?=$group1->link?>/<?=$group2->link?>/<?=$group3->link?>/"><?=$group3->name?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
