<?php
namespace Entity;

use ReflectionException;
use Models\Product\Product as ModelProduct;

class Product extends Entity
{
    public int $id;
    public ?int $xmlId = null;
    public $ieId = null;
    public string $name;
    public ?bool $isActive = null;
    public ?\DateTime $activeFrom = null;
    public ?\DateTime $activeTo = null;

    public ?string $articul = null;

    public int $categoryId;
    public string $category;
    public string $categoryLink;

    public int $vendorId;
    public string $vendor;
    public ?string $vendorImage;

    public ?string $previewImage = null;
    public ?string $previewText = null;
    public int $previewTextTypeId = 1;
    public string $previewTextType = 'text';
    public ?string $detailImage = null;
    public ?string $detailText = null;
    public int $detailTextTypeId = 1;
    public string $detailTextType = 'text';

    public ?bool $isHit = null;
    public ?bool $isNew = null;
    public ?bool $isAction = null;
    public ?bool $isRecommend = null;

    public int $taxId = 1;
    public ?bool $isTaxIncluded = null;
    public string $tax;
    public float $taxValue;

    public int $quantity = 0;
    public ?int $discount = null;
    public float $price;
    public float $priceDiscount;
    public int $priceTypeId = 2;
    public string $priceType = 'Розничная';

    public int $currencyId = 1;
    public string $currency = 'RUB';
    public string $currencyLogo = '₽';
    public string $currencySign = 'р.';
    public float $currencyRate = 1;

    public int $unitId = 1;
    public string $unit = 'штука';
    public string $unitSign = 'шт.';

    public ?bool $isWarranty = null;
    public int $warrantyPeriodId = 2;
    public string $warranty;

    public int $views = 0;
    public int $sort = 500;
    public \DateTime $created;
    public ?\DateTime $updated = null;

    public array $prices = []; // цены товара разных типов
    public array $images = []; // изображения товара
    public array $stores = []; // количество на складах

    /**
     * Возвращает массив полей для маппинга
     * @return array
     */
    public function getFields()
    {
        return [
            'id'                   => ['type' => 'int', 'field' => 'id'],
            'xml_id'               => ['type' => 'int', 'field' => 'xmlId'],
            'ie_id'                => ['type' => 'int', 'field' => 'ieId'],
            'name'                 => ['type' => 'string', 'field' => 'name'],
            'active'               => ['type' => 'bool', 'field' => 'isActive'],
            'active_from'          => ['type' => 'datetime', 'field' => 'activeFrom'],
            'active_to'            => ['type' => 'datetime', 'field' => 'activeTo'],
            'articul'              => ['type' => 'string', 'field' => 'articul'],
            'category_id'          => ['type' => 'int', 'field' => 'categoryId'],
            'category'             => ['type' => 'string', 'field' => 'category'],
            'category_link'        => ['type' => 'string', 'field' => 'categoryLink'],
            'vendor_id'            => ['type' => 'int', 'field' => 'vendorId'],
            'vendor'               => ['type' => 'string', 'field' => 'vendor'],
            'vendor_image'         => ['type' => 'string', 'field' => 'vendorImage'],
            'preview_image'        => ['type' => 'string', 'field' => 'previewImage'],
            'preview_text'         => ['type' => 'string', 'field' => 'previewText'],
            'preview_text_type_id' => ['type' => 'int', 'field' => 'previewTextTypeId'],
            'preview_text_type'    => ['type' => 'string', 'field' => 'previewTextType'],
            'detail_image'         => ['type' => 'string', 'field' => 'detailImage'],
            'detail_text'          => ['type' => 'string', 'field' => 'detailText'],
            'detail_text_type_id'  => ['type' => 'int', 'field' => 'detailTextTypeId'],
            'detail_text_type'     => ['type' => 'string', 'field' => 'detailTextType'],
            'is_hit'               => ['type' => 'bool', 'field' => 'isHit'],
            'is_new'               => ['type' => 'bool', 'field' => 'isNew'],
            'is_action'            => ['type' => 'bool', 'field' => 'isAction'],
            'is_recommend'         => ['type' => 'bool', 'field' => 'isRecommend'],
            'tax_id'               => ['type' => 'int', 'field' => 'taxId'],
            'tax_included'         => ['type' => 'bool', 'field' => 'isTaxIncluded'],
            'tax'                  => ['type' => 'string', 'field' => 'tax'],
            'tax_value'            => ['type' => 'float', 'field' => 'taxValue'],
            'quantity'             => ['type' => 'int', 'field' => 'quantity'],
            'discount'             => ['type' => 'float', 'field' => 'discount'],
            'price'                => ['type' => 'float', 'field' => 'price'],
            'price_discount'       => ['type' => 'float', 'field' => 'priceDiscount'],
            'price_type_id'        => ['type' => 'int', 'field' => 'priceTypeId'],
            'price_type'           => ['type' => 'string', 'field' => 'priceType'],
            'currency_id'          => ['type' => 'int', 'field' => 'currencyId'],
            'currency'             => ['type' => 'string', 'field' => 'currency'],
            'currency_logo'        => ['type' => 'string', 'field' => 'currencyLogo'],
            'currency_sign'        => ['type' => 'string', 'field' => 'currencySign'],
            'currency_rate'        => ['type' => 'float', 'field' => 'currencyRate'],
            'unit_id'              => ['type' => 'int', 'field' => 'unitId'],
            'unit'                 => ['type' => 'string', 'field' => 'unit'],
            'unit_sign'            => ['type' => 'string', 'field' => 'unitSign'],
            'is_warranty'          => ['type' => 'bool', 'field' => 'isWarranty'],
            'warranty_period_id'   => ['type' => 'int', 'field' => 'warrantyPeriodId'],
            'warranty'             => ['type' => 'string', 'field' => 'warranty'],
            'views'                => ['type' => 'int', 'field' => 'views'],
            'sort'                 => ['type' => 'int', 'field' => 'sort'],
            'created'              => ['type' => 'datetime', 'field' => 'created'],
            'updated'              => ['type' => 'datetime', 'field' => 'updated'],
            'prices'               => ['type' => 'array', 'field' => 'prices'],
            'images'               => ['type' => 'array', 'field' => 'images'],
            'stores'               => ['type' => 'array', 'field' => 'stores'],
        ];
    }

    /**
     * Сохранение товара в БД
     * @return bool|int
     * @throws ReflectionException
     */
    public function save()
    {
        return (new ModelProduct())->init($this)->save();
    }

    /**
     * Возвращает массив объектов товаров без цен
     * @param array $params
     * @return Product
     */
    public static function get($id, $params = [])
    {
        $item = ModelProduct::get($id, $params);
        $product = (new self())->init($item);
        $product->prices = ProductPrice::getPrices($product->id, $params['price_types'], $params);
        $product->images = ProductImage::getImages($product->id, $params);
        $product->stores = ProductStore::getStores($product->id, $params);
        return $product;
    }

    /**
     * Возвращает массив объектов товаров без цен
     * @param array $params
     * @return array
     */
    public static function getList($params = [])
    {
        $list = ModelProduct::getList($params);

        $res = [];
        if (!empty($list) && is_array($list)) {
            foreach ($list as $item) {
                $res[] = (new self())->init($item);
            }
        }

        return $res;
    }

    /**
     * Возвращает массив объектов товаров с ценами
     * @param array $params
     * @return array
     */
    public static function getPriceList($params = [])
    {
        $list = self::getList($params);

        if (!empty($list) && is_array($list)) {
            foreach ($list as $item) {
                $item->prices = ProductPrice::getPrices($item->id, $params['price_types'], $params);
            }
        }

        return $list;
    }

    /**
     * Инициализирует массив объектов цен товара
     * @param Product $product
     * @return Product
     */
    public static function getPrices(self $product)
    {
        if (!empty($product->prices) && is_array($product->prices)) {
            foreach ($product->prices as &$price) {
                $price = (new ProductPrice())->init($price);
            }
        }

        return $product;
    }
}
