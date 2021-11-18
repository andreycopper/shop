<?php

namespace Controllers;

use Models\Group;
use System\Request;
use Models\OrderItem;
use Models\Product\Product;
use Exceptions\NotFoundException;

class Catalog extends Controller
{
    protected $page_count = 20;

    protected function actionDefault()
    {
        $this->view->display('catalog/catalog');
    }

    /**
     * Показ товара/списка товаров данной категории
     * @param $elem
     * @throws NotFoundException
     */
    protected function actionShow($elem)
    {
        if (is_numeric($elem)) { // показ конкретного товара
            $item = Product::getPrice(intval($elem), $this->user->price_types);
            if (!empty($item)) {
                Product::addProductView($item->id); // добавляем просмотр товару
                $this->set('item', $item); // товар
                $this->view->display('product/item');
            } else throw new NotFoundException('Товар не найден');
        }
        else { // список товаров категории
            $group = Group::getByField('link', $elem, true);

            if (!empty($group->id)) {
                $filters = $this->getFilterParams();
                $this->set('group', $group); // категория товаров
                $this->set('sub_groups', Group::getSubGroups(intval($group->id))); // подкатегории
                $this->set('total_pages', ceil(Product::getCountByGroup([$group->id], $this->user->price_type_id, $filters) / $this->page_count)); // всего страниц для пагинации
                $this->set('range', Product::getRange($group->id, $this->user->price_type_id)); // min и max цена для фильтра
                $this->set('vendors', Product::getVendors($group->id)); // производители для фильтра
                $this->set('filters', $filters); // производители для фильтра
                $this->set('items', Product::getPriceList(
                    $group->id,
                    $this->user->price_type_id,
                    $this->user->price_types,
                    $this->page_current,
                    $this->page_count,
                    $filters,
                    !empty(Request::get('sort')) && in_array(Request::get('sort'), ['views', 'name', 'price']) ? Request::get('sort') : 'views',
                    !empty(Request::get('order')) && mb_strtolower(Request::get('order')) === 'desc' ? 'DESC' : 'ASC'
                )); // товары
                $this->view->display('catalog/list');
            } else throw new NotFoundException('Категория не найдена');
        }
    }

    /**
     * Формирует массив фильтров для выборки
     * @return array
     */
    private function getFilterParams()
    {
        $params = Request::get();
        $res = [];
        if (!empty($params) && is_array($params)) {
            foreach ($params as $key => $param) {
                if (in_array($key, ['page', 'sort', 'order'])) continue;
                $res[$key] = is_array($param) ? $param : explode('-', $param);
            }
        }
        return $res;
    }

    /**
     * Быстрый просмотр
     */
    protected function actionFastView()
    {
        if (Request::isPost()) {
            $item = Request::post('id');
            $item = Product::getPrice(intval($item), $this->user->price_types);

            if (!empty($item)) {
                Product::addProductView($item->id); // добавляем просмотр товару
                $this->set('item', $item); // товар
                echo $this->view->render('product/fast');
            }
        }
    }

    /**
     * Быстрый просмотр
     */
    protected function actionCompare()
    {
        echo $this->view->render('product/compare');
    }

    /**
     * Быстрый просмотр
     */
    protected function actionFavorites()
    {
        echo $this->view->render('product/favorites');
    }

    /**
     * Добавляет товар в корзину
     */
    protected function actionAddToCart()
    {
        if (Request::isPost()) {
            $item = Request::post(); // добавляемый товар
            $res = OrderItem::add($this->user, intval($item['id']), intval($item['count']));
            self::result($res, $res ? OrderItem::getCount() : 'Не удалось добавить товар в корзину');
        }
    }
}
