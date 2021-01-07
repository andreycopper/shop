<?php

namespace App\Controllers;

use App\Models\Group;
use App\Models\User;
use App\System\Logger;
use App\Models\Product;
use App\System\Request;
use App\Models\OrderItem;
use App\System\Pagination;
use App\Exceptions\DbException;
use App\Exceptions\NotFoundException;

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
        $price_type = User::isAuthorized() ? $this->view->user['price_type_id'] : 2;

        if (is_numeric($elem)) {
            $this->view->item = Product::getFullInfoById(intval($elem), $price_type);

            if (!empty($this->view->item)) {
                Product::addProductView($this->view->item->id); // добавляем просмотр товару

                $this->view->display('catalog/product');
            } else {
                $exc = new NotFoundException('Товар не найден');
                Logger::getInstance()->error($exc);
                throw $exc;
            }

        } else {
            $this->view->group = Group::getByField('name', $elem, true); // категория товаров

            if (!empty($this->view->group)) {
                $items = Product::getListByGroup(intval($this->view->group->id), $price_type, true); // список товаров данной категории
                $items = Pagination::make($items, $this->perPage); // массив страниц

                $this->view->subGroups  = Group::getSubGroups(intval($this->view->group->id), true); // подкатегории
                $this->view->items      = $items[$this->view->current_page] ?? null; // список товаров данной страницы
                $this->view->item_pages = $items['pages'] ?? null; // страницы данной категории

                $this->view->display('catalog/catalog_view');
            }
            else {
                $exc = new NotFoundException('Категория не найдена');
                Logger::getInstance()->error($exc);
                throw $exc;
            }
        }
    }

    /**
     * Добавляет товар в корзину
     */
    protected function actionAddToCart()
    {
        if (Request::isPost()) {
            $product = Request::post();
            OrderItem::add((int)$product['id'], (int)$product['count'], (int)$product['price_type'], Request::isAjax());
        }
    }
}
