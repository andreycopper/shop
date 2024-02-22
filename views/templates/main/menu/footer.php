<?php if (!empty($menuMain[0]) && is_array($menuMain[0])): ?>
    <?php foreach ($menuMain[0] as $footerMenuItem): ?>
        <?php if (!empty($footerMenuItem['footer'])): ?>
            <div class="footer-menu">
                <a href="/<?= $footerMenuItem['link'] ?>/" class="footer-title">
                    <?= $footerMenuItem['name'] ?>
                </a>

                <?php if (!empty($menuMain[$footerMenuItem['id']])): ?>
                    <ul>
                        <?php foreach ($menuMain[$footerMenuItem['id']] as $footerSubMenuItem): ?>
                            <?php if (!empty($footerSubMenuItem['footer'])): ?>
                                <li>
                                    <a href="/<?= $footerMenuItem['link'] ?>/<?= $footerSubMenuItem['link'] ?>/" class="footer-link">
                                        <?= $footerSubMenuItem['name'] ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif;
