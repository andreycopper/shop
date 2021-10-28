<?php
use System\Request;
$order = Request::get('order');
$sort = Request::get('sort');
$display = $_COOKIE['display'] ?? null;
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
                        <?= (empty($order) || $order === 'view') ? 'active' : '' ?>
                        <?= (empty($order) || $order === 'view') && !empty($sort) && $sort === 'desc' ? 'desc' : '' ?>">
                        <a href="?order=view<?=(((empty($order) || $order === 'view') && empty($sort)) ? '&sort=desc' : '')?>">
                            По популярности
                        </a>
                    </div>
                    <div class="sort-product name
                        <?=(!empty($order) && $order === 'name' ? 'active' : '')?>
                        <?=(!empty($order) && $order === 'name' && !empty($sort) && $sort === 'desc' ? 'desc' : '')?>">
                        <a href="?order=name<?=((!empty($order) && $order === 'name' && empty($sort)) ? '&sort=desc' : '')?>">
                            По алфавиту
                        </a>
                    </div>
                    <div class="sort-product price
                        <?=(!empty($order) && $order === 'price' ? 'active' : '')?>
                        <?=(!empty($order) && $order === 'price' && !empty($sort) && $sort === 'desc' ? 'desc' : '')?>">
                        <a href="?order=price<?=((!empty($order) && $order === 'price' && empty($sort)) ? '&sort=desc' : '')?>">
                            По цене
                        </a>
                    </div>
                </div>

                <div class="product-filter-view">
                    <a href="" data-view="blocks" title="плитки"
                       class="view-filter-product blocks <?=((empty($display) || $display === 'blocks') ? 'active' : '')?>"><i></i>
                    </a>
                    <a href="" data-view="list" title="список"
                       class="view-filter-product list <?=((!empty($display) && $display === 'list') ? 'active' : '')?>"><i></i>
                    </a>
                    <a href="" data-view="table" title="таблица"
                       class="view-filter-product table <?=((!empty($display) && $display === 'table') ? 'active' : '')?>"><i></i>
                    </a>
                </div>
            </div>

            <?= $this->render('catalog/view_' . ($display ?? 'blocks')) ?>
        <?php else: ?>
            <div class="product-container">
                <p class="required">Товары не найдены</p>
            </div>
        <?php endif; ?>

        <?= $this->render('pagination') ?>
    </div>
</div>
