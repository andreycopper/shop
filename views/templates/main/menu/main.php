<?php if (!empty($menu) && is_array($menu)): ?>
    <?php foreach ($menu[0] as $menuItem): ?>
        <div class="nav-item nav-wrap">
            <a href="/<?=$menuItem->link?>/" class="nav-item-link <?=!empty($this->menu[$menuItem->id]) ? 'dropdown' : '';?>">
                <?=$menuItem->name?>
                <span class="nav-item-bottom"></span>
            </a>

            <?php if (!empty($this->menu[$menuItem->id])) : ?>
                <ul class="dropdown-menu">
                    <?php foreach ($this->menu[$menuItem->id] as $subMenuItem): ?>
                        <li><a href="/<?=$menuItem->link?>/<?=$subMenuItem->link?>/"><?=$subMenuItem->name?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
