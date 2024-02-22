<?php
namespace Entity;

use ReflectionException;
use Models\Product\ProductPrice as ModelProductPrice;

class ProductPrice extends Entity
{
    public int $productId;
    public int $priceTypeId;
    public string $priceType;
    public float $price;
    public float $priceDiscount;
    public ?float $discount;

    public int $currencyId;
    public string $currency;
    public string $currencyLogo;
    public string $currencySign;
    public float $currencyRate;

    public int $taxId;
    public bool $isTaxIncluded;
    public string $tax;
    public float $taxValue;

    public \DateTime $created;
    public ?\DateTime $updated = null;

    /**
     * Возвращает массив полей для маппинга
     * @return array
     */
    public function getFields()
    {
        return [
            'product_id'     => ['type' => 'int', 'field' => 'productId'],
            'price_type_id'  => ['type' => 'int', 'field' => 'priceTypeId'],
            'price_type'     => ['type' => 'string', 'field' => 'priceType'],
            'price'          => ['type' => 'float', 'field' => 'price'],
            'price_discount' => ['type' => 'float', 'field' => 'priceDiscount'],
            'discount'       => ['type' => 'float', 'field' => 'discount'],
            'currency_id'    => ['type' => 'int', 'field' => 'currencyId'],
            'currency'       => ['type' => 'string', 'field' => 'currency'],
            'currency_logo'  => ['type' => 'string', 'field' => 'currencyLogo'],
            'currency_sign'  => ['type' => 'string', 'field' => 'currencySign'],
            'currency_rate'  => ['type' => 'float', 'field' => 'currencyRate'],
            'tax_id'         => ['type' => 'int', 'field' => 'taxId'],
            'tax_included'   => ['type' => 'bool', 'field' => 'isTaxIncluded'],
            'tax'            => ['type' => 'string', 'field' => 'tax'],
            'tax_value'      => ['type' => 'float', 'field' => 'taxValue'],
            'created'        => ['type' => 'datetime', 'field' => 'created'],
            'updated'        => ['type' => 'datetime', 'field' => 'updated'],
        ];
    }

    /**
     * Сохранение цены товара в БД
     * @return bool|int
     * @throws ReflectionException
     */
    public function save()
    {
        return (new ModelProductPrice())->init($this)->save();
    }

    /**
     * Возвращает массив с основной ценой товара
     * @param int $product_id
     * @param int $price_type_id
     * @param array $params
     * @return array
     */
    public static function getPrice(int $product_id, int $price_type_id, array $params = [])
    {
        $list = ModelProductPrice::getPrice($product_id, $price_type_id, $params);

        $res = [];
        if (!empty($list) && is_array($list)) {
            foreach ($list as $item) {
                $res[] = (new self())->init($item);
            }
        }

        return $res;
    }

    /**
     * Возвращает массив цен товара
     * @param int $product_id
     * @param array $price_types
     * @param array $params
     * @return array
     */
    public static function getPrices(int $product_id, array $price_types = [], array $params = [])
    {
        $list = ModelProductPrice::getPrices($product_id, $price_types, $params);

        $res = [];
        if (!empty($list) && is_array($list)) {
            foreach ($list as $item) {
                $res[] = (new self())->init($item);
            }
        }

        return $res;
    }
}
