<?php
namespace Models\User;

use System\Db;
use Models\Model;

class UserPriceType extends Model
{
    protected static $db_table = 'user_price_types';

    public ?int $user_group_id;
    public ?int $user_id;
    public int $price_type_id;
    public string $created;

    /**
     * Возвращает список доступных для просмотра типов цен
     * @param $user_id - id пользователя
     * @param $user_group_id - id группы пользователя
     * @param array|null $params - массив параметров
     * @return array
     */
    public static function getListByUser($user_id, $user_group_id, ?array $params = [])
    {
        $params += ['active' => true];

        $db = Db::getInstance();
        $active_user = !empty($params['active']) ? 'AND u.active IS NOT NULL' : '';
        $active_group = !empty($params['active']) ? 'AND ug.active IS NOT NULL' : '';
        $db->params = ['user_id' => $user_id, 'user_group_id' => $user_group_id];

        $db->sql = "
            SELECT upt.price_type_id 
            FROM shop.user_price_types upt
            LEFT JOIN shop.users u ON u.id = upt.user_id
            LEFT JOIN shop.user_groups ug ON ug.id = upt.user_group_id
            WHERE (u.id = :user_id {$active_user}) OR (ug.id = :user_group_id {$active_group})";

        $data = $db->query();

        $res = [];
        if (!empty($data) && is_array($data)) {
            foreach ($data as $elem) {
                $res[] = $elem['price_type_id'];
            }
        }

        return array_unique($res);
    }
}
