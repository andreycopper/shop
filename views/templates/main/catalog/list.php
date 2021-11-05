<?php
use System\Request;
$order = Request::get('order') ?? 'views';
$sort = Request::get('sort') ?? 'asc';
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
            <div class="product-sort-view">
                <div class="product-sort">
                    <div class="sort-product show
                        <?= ($order === 'views') ? 'active' : '' ?>
                        <?= ($order === 'views' && $sort === 'desc') ? 'desc' : '' ?>">
                        <a href="?order=views<?= ($order === 'views' && $sort === 'asc') ? '&sort=desc' : '' ?>">
                            По популярности
                        </a>
                    </div>
                    <div class="sort-product name
                        <?= ($order === 'name') ? 'active' : '' ?>
                        <?= ($order === 'name' && $sort === 'desc') ? 'desc' : '' ?>">
                        <a href="?order=name<?= ($order === 'name' && $sort === 'asc') ? '&sort=desc' : '' ?>">
                            По алфавиту
                        </a>
                    </div>
                    <div class="sort-product price
                        <?= ($order === 'price') ? 'active' : '' ?>
                        <?= ($order === 'price' && $sort === 'desc') ? 'desc' : '' ?>">
                        <a href="?order=price<?= ($order === 'price' && $sort === 'asc') ? '&sort=desc' : '' ?>">
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
