<?php
namespace Controllers;

use Entity\Product;
use System\Request;
use Entity\Category;
use Models\OrderItem;
use Exceptions\NotFoundException;
use Models\Product\Product as ModelProduct;

class Catalog extends Controller
{
    protected int $elementsPerPage = 20;

    /**
     * Показ категорий каталога (+)
     * @return void
     */
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
            $item = Product::get(intval($elem), ['price_type_id' => $this->user->priceTypeId, 'price_types' => $this->user->priceTypes]); // TODO очистить id регуляркой

            if (!empty($item->id)) {
                ModelProduct::addView($item->id);
                $this->set('item', $item);

                $this->display('product/item');
            }
            else throw new NotFoundException('Товар не найден');
        }

        else { // список товаров категории
            $category = Category::getByName($elem);

            if (!empty($category->id)) {
                //$filters = $this->getFilterParams();
                $items = ModelProduct::getPriceList([
                    'category_id' => $category->id,
                    'price_type_id' => $this->user->priceTypeId,
                    'price_types' => $this->user->priceTypes,
                    'page_number' => $this->currentPage - 1,
                    'elements_per_page' => $this->elementsPerPage,
                ]);


                $this->set('category', $category);
                $this->set('items', $items);
                $this->set('totalItems', ModelProduct::getCount(['category_id' => $category->id]));
                $this->set('priceRange', ModelProduct::getRange(['category_id' => $category->id, 'price_type_id' => $this->user->priceTypeId]));
                $this->set('vendors', ModelProduct::getVendors(['category_id' => $category->id]));
                //$this->set('filters', $filters); // производители для фильтра

                $this->display('catalog/list');
            } else throw new NotFoundException('Категория товаров не найдена');
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
            $item = Product::getPrice(intval($item), $this->user->price_type_id);

            if (!empty($item)) {
                Product::addProductView($item->id); // добавляем просмотр товару
                $this->set('item', $item); // товар
                echo $this->view->render('product/fast');
            }
        }
    }

    /**
     * Сравнение товаров
     */
    protected function actionCompare()
    {
        echo $this->view->render('product/compare');
    }

    /**
     * Избранные товары
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
