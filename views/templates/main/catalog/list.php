<?php
use System\Request;

$sort = Request::get('sort') ?? 'views';
$order = Request::get('order') ?? 'asc';
$display = $_COOKIE['display'] ?? 'blocks';
?>

<div class="catalog-container">
    <div class="catalog-leftmenu">
        <?= $this->render('side/menu_catalog') ?>
        <?= $this->render('side/filter') ?>
        <?= $this->render('side/marketing') ?>
        <?= $this->render('side/subscribe') ?>
        <?= $this->render('side/news') ?>
        <?= $this->render('side/articles') ?>
    </div>

    <div class="catalog-main">
        <?php if (!empty($this->sub_groups) && is_array($this->sub_groups)): ?>
            <div class="subcategories-container">
                <?php foreach ($this->sub_groups as $sub_group): ?>
                    <div class="subcategories-item">
                        <a href="<?=$sub_group->link?>/">
                            <div class="subcategories-image">
                                <img src="/uploads/groups/<?=$sub_group->id?>/<?=$sub_group->image?>" alt="">
                            </div>
                            <div class="subcategories-title"><?=$sub_group->name?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($group->description)): ?>
            <div class="category-description">
                <?= $group->description ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($items) && is_array($items)): ?>
            <?php
                $get = Request::get() ?: [];
                if (!empty($get['sort'])) unset($get['sort']);
                if (!empty($get['order'])) unset($get['order']);
                $url = http_build_query($get);
            ?>
            <div class="product-sort-view">
                <div class="product-sort">
                    <div class="sort-product show
                        <?= ($sort === 'views') ? 'active' : '' ?>
                        <?= ($sort === 'views' && $order === 'desc') ? 'desc' : '' ?>">
                        <a href="?<?= $url . ($url ? '&' : '') ?>sort=views<?= ($sort === 'views' && $order === 'asc') ? '&order=desc' : '' ?>">
                            По популярности
                        </a>
                    </div>
                    <div class="sort-product name
                        <?= ($sort === 'name') ? 'active' : '' ?>
                        <?= ($sort === 'name' && $order === 'desc') ? 'desc' : '' ?>">
                        <a href="?<?= $url . ($url ? '&' : '') ?>sort=name<?= ($sort === 'name' && $order === 'asc') ? '&order=desc' : '' ?>">
                            По алфавиту
                        </a>
                    </div>
                    <div class="sort-product price
                        <?= ($sort === 'price') ? 'active' : '' ?>
                        <?= ($sort === 'price' && $order === 'desc') ? 'desc' : '' ?>">
                        <a href="?<?= $url . ($url ? '&' : '') ?>sort=price<?= ($sort === 'price' && $order === 'asc') ? '&order=desc' : '' ?>">
                            По цене
                        </a>
                    </div>
                </div>

                <div class="product-filter-view">
                    <a href="" data-view="blocks" title="плитки"
                       class="view-filter-product blocks <?= $display === 'blocks' ? 'active' : ''?>"><i></i>
                    </a>
                    <a href="" data-view="list" title="список"
                       class="view-filter-product list <?= $display === 'list' ? 'active' : '' ?>"><i></i>
                    </a>
                    <a href="" data-view="table" title="таблица"
                       class="view-filter-product table <?= $display === 'table' ? 'active' : '' ?>"><i></i>
                    </a>
                </div>
            </div>

            <?= $this->render("catalog/view_{$display}") ?>
        <?php else: ?>
            <div class="product-container">
                <p class="required">Товары не найдены</p>
            </div>
        <?php endif; ?>

        <?= $this->render('pagination') ?>
    </div>
</div>

<script>
    $(function () {
        /* смена режима просмотра каталога */
        $('.view-filter-product').on('click', function (e) {
            $('.view-filter-product').removeClass('active');
            $(this).addClass('active');
            $.cookie('display', $(this).attr('data-view'), {expires: 1, path: '/'});
        });
    });
</script>
