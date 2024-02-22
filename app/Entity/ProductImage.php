<?php
namespace Entity;

use ReflectionException;
use Models\Product\ProductImage as ModelProductImage;

class ProductImage extends Entity
{
    public int $id;
    public int $productId;
    public string $image;
    public int $sort = 500;
    public \DateTime $created;
    public ?\DateTime $updated = null;

    /**
     * Возвращает массив полей для маппинга
     * @return array
     */
    public function getFields()
    {
        return [
            'id'         => ['type' => 'int', 'field' => 'id'],
            'product_id' => ['type' => 'int', 'field' => 'productId'],
            'image'      => ['type' => 'string', 'field' => 'image'],
            'sort'       => ['type' => 'int', 'field' => 'sort'],
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
        return (new ModelProductImage())->init($this)->save();
    }

    /**
     * Возвращает массив объектов изображений товара
     * @param int $product_id - id товара
     * @param array $params
     * @return array
     */
    public static function getImages(int $product_id, array $params = [])
    {
        $list = ModelProductImage::getImages($product_id, $params);

        $res = [];
        if (!empty($list) && is_array($list)) {
            foreach ($list as $item) {
                $res[] = (new self())->init($item);
            }
        }

        return $res;
    }
}
