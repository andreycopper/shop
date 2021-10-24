<?php

namespace Controllers;

use Models\Group;
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
            $this->view->item = Product::getPricesItem(intval($elem), [$this->view->user->price_type_id]);

            if (!empty($this->view->item)) {
                Product::addProductView($this->view->item->id); // добавляем просмотр товару
                $this->view->display('catalog/product');
            } else throw new NotFoundException('Товар не найден');
        }
        else {
            $this->view->group = Group::getByField('link', $elem, true); // категория товаров

            if (!empty($this->view->group)) {
                $items = Product::getPricesList([intval($this->view->group->id)], [intval($this->view->user->price_type_id)]);
                $items = Pagination::make($items, $this->perPage); // массив страниц
                $this->view->items = $items[$this->view->pageCurrent] ?? null; // список товаров данной страницы
                $this->view->item_pages = $items['pages'] ?? null; // страницы данной категории
                $this->view->subGroups  = Group::getSubGroups(intval($this->view->group->id)); // подкатегории
                $this->view->display('catalog/catalog_view');
            } else throw new NotFoundException('Категория не найдена');
        }
    }

    /**
     * Добавляет товар в корзину
     */
    protected function actionAddToCart()
    {
        if (Request::isPost()) {
            $item = Request::post(); // добавляемый товар
            $product = Product::getPriceItem(intval($item['id']), $this->user->price_type_id); // товар в каталоге

            if (empty($product) || !OrderItem::checkCartProduct($product, intval($item['id']), intval($item['count'])))
                self::result(false, 'Товар отсутствует на складе');

            $res = OrderItem::add($this->user, intval($item['id']), intval($item['count']));
            self::result($res, $res ? OrderItem::getCount() : 'Не удалось добавить товар в корзину');
        }
    }
}
