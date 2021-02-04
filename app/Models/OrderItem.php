<?php

namespace App\Models;

use App\System\Db;
use App\System\Logger;
use App\Exceptions\DbException;
use App\Exceptions\EditException;
use App\Exceptions\DeleteException;

class OrderItem extends Model
{
    protected static $table = 'order_items';
    public $id;             // id записи
    public $order_id;       // id заказа
    public $user_id;        // id пользователя
    public $user_hash;      // hash пользователя
    public $product_id;     // id товара
    public $price_type_id;  // id типа цен
    public $count;          // количество
    public $coupon_id;      // id купона
    public $discount;       // скидка
    public $price;          // цена
    public $sum;            // сумма
    public $discount_price; // цена со скидкой
    public $discount_sum;   // сумма
    public $created;        // дата создания записи

    /**
     * Получает товар в корзине пользователя по его id
     * @param int $user_id
     * @param int $product_id
     * @param bool $object
     * @return false|mixed
     * @throws DbException
     */
    public static function getByUserId(int $user_id, int $product_id, $object = true)
    {
        $sql = "
            SELECT * 
            FROM order_items 
            WHERE user_id = :user_id AND product_id = :product_id AND order_id IS NULL";
        $params = [
            ':user_id' => $user_id,
            ':product_id' => $product_id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    /**
     * Получает товар в корзине пользователя по его хэшу
     * @param string $user_hash
     * @param int $product_id
     * @param bool $object
     * @return false|mixed
     * @throws DbException
     */
    public static function getByUserHash(string $user_hash, int $product_id, $object = true)
    {
        $sql = "
            SELECT * 
            FROM order_items 
            WHERE user_id = 2 AND user_hash = :user_hash AND product_id = :id AND order_id IS NULL";
        $params = [
            ':user_hash' => $user_hash,
            ':id' => $product_id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return !empty($data) ? array_shift($data) : false;
    }

    public static function getListByUserId($user_id, $active = false, $object = true)
    {
        $where = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $sql = "
            SELECT oi.id, oi.order_id, oi.user_id, oi.user_hash, oi.product_id, oi.price_type_id, oi.count, 
                   oi.coupon_id, oi.discount, oi.price, oi.sum, oi.discount_price, oi.discount_sum, (oi.price - oi.discount_price) economy, oi.created, 
                   pt.name AS price_type, 
                   t.value AS tax,
                   p.name, p.preview_image, p.quantity,
                   u.sign AS unit,
                   curr.sign AS currency,
                   v.name AS vendor 
            FROM order_items oi 
            LEFT JOIN price_types pt 
                ON pt.id = oi.price_type_id 
            LEFT JOIN products p 
                ON p.id = oi.product_id 
            LEFT JOIN units u 
                ON u.id = p.unit_id
            LEFT JOIN currencies curr
                ON p.currency_id = curr.id
            LEFT JOIN taxes t 
                ON t.id = p.tax_id
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id
            WHERE oi.user_id = :user_id AND oi.order_id IS NULL {$where}
            ";
        $params = [
            ':user_id' => $user_id
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    public static function getListByUserHash($user_hash, $active = false, $object = true)
    {
        $where = !empty($active) ? 'AND p.active IS NOT NULL' : '';
        $sql = "
            SELECT oi.id, oi.order_id, oi.user_id, oi.user_hash, oi.product_id, oi.price_type_id, oi.count, 
                   oi.coupon_id, oi.discount, oi.price, oi.sum, oi.discount_price, oi.discount_sum, (oi.price - oi.discount_price) economy, oi.created, 
                   pt.name AS price_type, 
                   t.value AS tax,
                   p.name, p.preview_image, p.quantity,
                   u.sign AS unit,
                   curr.sign AS currency,
                   v.name AS vendor 
            FROM order_items oi 
            LEFT JOIN price_types pt 
                ON pt.id = oi.price_type_id 
            LEFT JOIN products p 
                ON p.id = oi.product_id 
            LEFT JOIN units u 
                ON u.id = p.unit_id
            LEFT JOIN currencies curr
                ON p.currency_id = curr.id
            LEFT JOIN taxes t 
                ON t.id = p.tax_id
            LEFT JOIN vendors v 
                ON p.vendor_id = v.id
            WHERE oi.user_hash = :user_hash AND oi.order_id IS NULL {$where}";
        $params = [
            ':user_hash' => $user_hash
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Получает количество товаров в корзине
     * @return false|mixed
     * @throws DbException
     */
    public static function getCount()
    {
        $user = User::getCurrent();

        if (!empty($user['id'])) {
            $sql = "SELECT count(*) AS count FROM order_items WHERE user_id = :user_id AND order_id IS NULL";
            $params = [
                ':user_id' => $user['id']
            ];
        } else {
            $sql = "SELECT count(*) AS count FROM order_items WHERE user_id = 2 AND user_hash = :user_hash AND order_id IS NULL";
            $params = [
                ':user_hash' => $_COOKIE['user']
            ];
        }

        $db = new Db();
        $data = $db->query($sql, $params);
        return !empty($data) ? array_shift($data)['count'] : false;
    }

    /**
     * Получает корзину анонимного пользователя
     * @param string $user_hash
     * @param bool $object
     * @return array|false
     * @throws DbException
     */
    public static function getAnonymous(string $user_hash, bool $object = true)
    {
        $sql = "
            SELECT * 
            FROM order_items 
            WHERE user_hash = :user_hash AND user_id = 2 AND order_id IS NULL";
        $params = [
            ':user_hash' => $user_hash
        ];
        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }

    /**
     * Добавляет товар в корзину (ajax)
     * @param int $product_id
     * @param int $count
     * @param int $price_type
     * @param bool $isAjax
     * @return OrderItem|false|mixed
     * @throws DbException
     */
    public static function add(int $product_id, int $count, int $price_type, bool $isAjax = false)
    {
        if (!empty($product_id)) {
            if (!empty($count)) {
                $product = Product::getByIdWithRate($product_id, $price_type, true);

                if (!empty($product->id)) {
                    if (intval($product->quantity) >= $count) {

                        $user = User::getCurrent();

                        if (!empty($user['id'])) $item = self::getByUserId($user['id'], $product_id);
                        else $item = self::getByUserHash($_COOKIE['user'], $product_id);

                        if (empty($item->id)) {
                            $item = new self();
                            $item->user_id = $user['id'] ?? 2; // если не авторизован, пишем корзину на id = 2 (user)
                            $item->user_hash = $_COOKIE['user'];
                            $item->product_id = $product_id;
                        }

                        $item->price_type_id = $price_type;
                        $item->count = $count;
                        $item->price = round($product->price * $product->rate);
                        $item->sum = round($item->price * $count);
                        $item->created = date('Y-m-d');

                        if (!empty($product->discount)) {
                            $item->discount = $product->discount;
                            $item->discount_price = round($product->price * $product->rate * (100 - $product->discount) / 100);
                            $item->discount_sum = round($item->discount_price * $count);
                        }

                        if ($item->save()) {
                            if ($isAjax) {
                                echo json_encode([
                                    'result' => true,
                                    'count' => self::getCount()
                                ]);
                                die;
                            } else return $item;
                        } else $message = 'Не удалось сохранить в корзину ' . $product->name . ' в количестве ' . $count;
                    } else $message = 'На складе товаров меньше указанного количества';
                } else $message = 'Товар не найден';
            } else $message = 'Указано неверное количество товара';
        } else $message = 'Не указан товар';

        Logger::getInstance()->error(new EditException($message));
        echo json_encode([
            'result' => false,
            'message' => $message
        ]);
        die;
    }

    /**
     * Получает актуальную корзину пользователя
     * @param bool $format_price
     * @param string $coupon_code
     * @return array|false
     * @throws DbException
     */
    public static function getCart(bool $format_price = true, string $coupon_code = '')
    {
        $user = User::getCurrent();

        if (!empty($user['id'])) {
            //$coupon = $coupon_code ? Coupon::getByCodeUserId($coupon_code, $user['id'], true) : null;
            $items = self::getListByUserId($user['id']);
        }
        else {
            //$coupon = $coupon_code ? Coupon::getByCodeUserHash($coupon_code, $_COOKIE['user'], true) : null;
            $items = self::getListByUserHash($_COOKIE['user']);
        }

        if (!empty($items) && is_array($items)) {
            $count_items = 0;
            $sum = 0;
            $discount_sum = 0;
            $notavialable = [];

            foreach ($items as $key => $item) {
                if ($item->count > $item->quantity) { // на складах недостаточно товара
                    $notavialable[] = $item;
                    unset($items[$key]);
                    continue;
                }

                $outdated = str_replace('-', '', explode(' ', $item->created)[0]) < date('Ymd');
                if ($outdated) { // товар в корзине больше суток
                    if ($outdated) $message = 'Цены товаров, добавленных в корзину более суток назад обновлены';

                    $price = Product::getPrice($item->product_id, $item->price_type_id); // актуальные цены товара

                    $item->price = round($price->price * $price->rate);
                    $item->sum = round($item->price * $item->count);
                    $item->created = date('Y-m-d');
                    $item->discount = null;
                    $item->discount_price = null;
                    $item->discount_sum = null;
                    $item->economy = null;

                    if (!empty($price->discount)) { // на товар имеется скидка
                        $item->discount = $price->discount;
                        $item->discount_price = round($item->price * (100 - $price->discount) / 100);
                        $item->discount_sum = round($item->discount_price * $item->count);
                        $item->economy = round($item->price - $item->discount_price);
                    }

                    if (!self::factory($item)->save()) Logger::getInstance()->error(new EditException('Не удалось обновить в корзине товар id=' . $item->id));
                }

                $count_items += $item->count;
                $sum += $item->sum;
                $discount_sum += $item->discount_sum ?? $item->sum;

                if ($format_price) {
                    $item->price = number_format($item->price, 0, '.', ' ');
                    $item->sum = number_format($item->sum, 0, '.', ' ');
                    $item->discount_price = number_format($item->discount_price, 0, '.', ' ');
                    $item->discount_sum = number_format($item->discount_sum, 0, '.', ' ');
                    $item->economy = number_format($item->economy, 0, '.', ' ');
                }
            }

            $result = [
                'items'              => $items,
                'notavialable'       => $notavialable,
                'count_items'        => $count_items,
                'count_notavialable' => count($notavialable),
                'sum'                => $format_price ? number_format($sum, 0, '.', ' ') : $sum,
                'discount_sum'       => $format_price ? number_format($discount_sum, 0, '.', ' ') : $discount_sum,
                'economy'            => $format_price ? number_format($sum - $discount_sum, 0, '.', ' ') : ($sum - $discount_sum),
                'coupon'            => $coupon ?? null,
                'message'            => $message ?? ''
            ];
        }

        return $result ?? false;
    }

    /**
     * Пересчитывает корзину при изменении количества товара
     * @param int $product_id
     * @param int $count
     * @param int $price_type
     * @param bool $isAjax
     * @return array
     * @throws DbException
     */
    public static function recalc(int $product_id, int $count, int $price_type, bool $isAjax = false)
    {
        $item = OrderItem::add($product_id, $count, $price_type);

        if ($item) { // товар сохранен в корзине
            $cart = OrderItem::getCart(false);

            if ($cart) { // получена актуальная корзина
                $result = [
                    'result'              => true,
                    'item_price'          => number_format($item->price, 0, '.', ' '),
                    'item_sum'            => number_format($item->sum, 0, '.', ' '),
                    'item_discount_price' => $item->discount_price ? number_format($item->discount_price, 0, '.', ' ') : null,
                    'item_discount_sum'   => $item->discount_sum ? number_format($item->discount_sum, 0, '.', ' ') : null,
                    'item_economy'        => $item->discount_sum ? number_format(($item->sum - $item->discount_sum), 0, '.', ' ') : null,
                    'cart_sum'            => $cart['sum'],
                    'cart_discount_sum'   => $cart['discount_sum'],
                    'cart_economy'        => $cart['economy'],
                    'message'             => $cart['message'],
                ];

                if ($isAjax) {
                    echo json_encode($result);
                    die;
                } else return $result;
            } else $message = 'Не удалось обновить корзину';
        } else $message = 'Не удалось сохранить корзину';

        $result = [
            'result' => false,
            'message' => $message
        ];

        if ($isAjax) {
            echo json_encode($result);
            die;
        } else return $result;
    }

    /**
     * Удаляет товар из корзины
     * @param int $product_id
     * @return array
     * @throws DbException
     */
    public static function deleteItem(int $product_id)
    {
        $user = User::getCurrent();

        if (!empty($user['id'])) $item = OrderItem::getByUserId($user['id'], $product_id);
        else $item = OrderItem::getByUserHash($_COOKIE['user'], $product_id);

        if ($item) { // товар найден в корзине
            if ($item->delete()) { // удален товар из корзины
                $cart = OrderItem::getCart();

                if ($cart) { // получена актуальная корзина
                    return [
                        'result'              => true,
                        'count'               => $cart['count_items'],
                        'cart_sum'            => $cart['sum'],
                        'cart_discount_sum'   => $cart['discount_sum'],
                        'cart_economy'        => $cart['economy'],
                        'message'             => $cart['message'],
                    ];
                } else $message = 'Не удалось обновить корзину';
            } else $message = 'Не удалось удалить товар из корзины';
        } else $message = 'Не удалось найти товар в корзине';

        Logger::getInstance()->error(new DeleteException($message));
        return [
            'result' => false,
            'message' => $message
        ];
    }

    /**
     * Очищает корзину
     * @throws DbException
     */
    public static function clearCart()
    {
        $user = User::getCurrent();

        if (!empty($user['id'])) $items = self::getListByUserId($user['id']);
        else $items = self::getListByUserHash($_COOKIE['user']);

        if (!empty($items) && is_array($items)) { // найдены товары в корзине
            foreach ($items as $key => $item) {
                if (!$item->delete()) { // не удалось удалить товар
                    $message = 'Не удалось удалить товар с id=' . $item->product_id . ' пользователя с id=' . $item->user_id;
                    Logger::getInstance()->error(new DeleteException($message));

                    return [
                        'result' => false,
                        'message' => $message
                    ];
                }
            }
            return ['result' => true];
        } else $message = 'Не найдены товары в корзине';

        Logger::getInstance()->error(new DeleteException($message));

        return [
            'result' => false,
            'message' => $message
        ];
    }

    /**
     * Проверка наличия корзины анонимного пользователя и привязка ее к авторизованному
     * @throws DbException
     */
    public static function checkAnonymous()
    {
        $items = self::getAnonymous($_COOKIE['user']);

        if (!empty($items) && is_array($items)) {
            $user = User::getCurrent();

            if (!empty($user['id'])) {
                foreach ($items as $item) {
                    if (intval($user['id']) !== intval($item->user_id)) {
                        $cart_item = self::getByUserId($user['id'], $item->product_id);

                        if (!empty($cart_item->id)) { // в корзине авторизованного пользователя найден такой же товар, как и у анонимного
                            if (false === $cart_item->delete()) {
                                Logger::getInstance()->error(new DbException('Не удалось удалить анонимную корзину пользователя с id = ' . $user['id'] . ' и hash = ' . $_COOKIE['user']));
                            }
                        }

                        $item->user_id = $user['id'];
                        $item->price_type_id = $user['price_type_id'];

                        if (false === $item->save()) {
                            Logger::getInstance()->error(new DbException('Не удалось привязать анонимную корзину пользователя с id = ' . $user['id'] . ' и hash = ' . $_COOKIE['user']));
                        }
                    }
                }
            }
        }
    }

    public function filter_id($id)
    {
        return (int)$id;
    }

    public function filter_order_id($id)
    {
        return (int)$id;
    }

    public function filter_user_id($id)
    {
        return (int)$id;
    }

    public function filter_user_hash($text)
    {
        return strip_tags(trim($text));
    }

    public function filter_product_id($id)
    {
        return (int)$id;
    }

    public function filter_count($id)
    {
        return (int)$id;
    }

    public function filter_price($text)
    {
        return strip_tags(trim($text));
    }
}
