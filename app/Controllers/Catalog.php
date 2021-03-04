<?php

namespace Controllers;

use Models\Group;
use Models\User;
use System\Logger;
use Models\Product;
use System\Request;
use Models\OrderItem;
use System\Pagination;
use Exceptions\DbException;
use Exceptions\NotFoundException;

class Catalog extends Controller
{
    protected $perPage = 20;

    protected function actionDefault()
    {
        $this->view->display('catalog/catalog');
    }

    /**
     * Показ товара/списка товаров данной категории
     * @param $elem
     * @throws NotFoundException
     * @throws DbException
     */
    protected function actionShow($elem)
    {
        if (is_numeric($elem)) {
            $this->view->item = Product::getItemPrice(intval($elem), [$this->view->user->price_type_id]);
            //$this->view->item = Product::getFullInfoById(intval($elem), $this->view->user->price_type_id);var_dump($elem);die;

            if (!empty($this->view->item)) {
                Product::addProductView($this->view->item->id); // добавляем просмотр товару
                $this->view->display('catalog/product');
            } else throw new NotFoundException('Товар не найден');
        }
        else {
            $this->view->group = Group::getByField('link', $elem, true); // категория товаров

            if (!empty($this->view->group)) {
                $items = Product::getListPrice([intval($this->view->group->id)], [intval($this->view->user->price_type_id)]);
                $items = Pagination::make($items, $this->perPage); // массив страниц




                $this->view->subGroups  = Group::getSubGroups(intval($this->view->group->id), true); // подкатегории
                $this->view->items      = $items[$this->view->current_page] ?? null; // список товаров данной страницы
                $this->view->item_pages = $items['pages'] ?? null; // страницы данной категории
                $this->view->display('catalog/catalog_view');
            }
            else throw new NotFoundException('Категория не найдена');
        }
    }

    /**
     * Добавляет товар в корзину
     */
    protected function actionAddToCart()
    {
        if (Request::isPost()) {
            $product = Request::post();
            OrderItem::add(intval($product['id']), intval($product['count']), intval($product['price_type']), Request::isAjax());
        }
    }
}
