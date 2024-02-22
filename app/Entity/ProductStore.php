<?php
namespace Entity;

use ReflectionException;
use Models\Product\ProductStore as ModelProductStore;

class ProductStore extends Entity
{
    public int $productId;
    public int $storeId;
    public int $quantity;
    public string $name;
    public string $address;
    public \DateTime $created;
    public ?\DateTime $updated = null;

    /**
     * Возвращает массив полей для маппинга
     * @return array
     */
    public function getFields()
    {
        return [
            'product_id' => ['type' => 'int', 'field' => 'productId'],
            'store_id'   => ['type' => 'int', 'field' => 'storeId'],
            'quantity'   => ['type' => 'int', 'field' => 'quantity'],
            'name'       => ['type' => 'string', 'field' => 'name'],
            'address'    => ['type' => 'string', 'field' => 'address'],
            'created'    => ['type' => 'datetime', 'field' => 'created'],
            'updated'    => ['type' => 'datetime', 'field' => 'updated'],
        ];
    }

    /**
     * Сохранение цены товара в БД
     * @return bool|int
     * @throws ReflectionException
     */
    public function save()
    {
        return (new ModelProductStore())->init($this)->save();
    }

    /**
     * Возвращает массив объектов складов с количеством товара
     * @param int $product_id - id товара
     * @param array $params
     * @return array
     */
    public static function getStores(int $product_id, array $params = [])
    {
        $list = ModelProductStore::getStores($product_id, $params);

        $res = [];
        if (!empty($list) && is_array($list)) {
            foreach ($list as $item) {
                $res[] = (new self())->init($item);
            }
        }

        return $res;
    }
}
