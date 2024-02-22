<?php if (!empty($menuMain[0]) && is_array($menuMain[0])): ?>
    <?php foreach ($menuMain[0] as $menuItem): ?>
        <div class="nav-item nav-wrap">
            <a href="/<?= $menuItem['link'] ?>/" class="nav-item-link <?= !empty($menuMain[$menuItem['id']]) ? 'dropdown' : '' ?>">
                <?= $menuItem['name'] ?>
                <span class="nav-item-bottom"></span>
            </a>

            <?php if (!empty($menuMain[$menuItem['id']])) : ?>
                <ul class="dropdown-menu">
                    <?php foreach ($menuMain[$menuItem['id']] as $subMenuItem): ?>
                        <li>
                            <a href="/<?= $menuItem['link'] ?>/<?= $subMenuItem['link'] ?>/">
                                <?= $subMenuItem['name'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif;
