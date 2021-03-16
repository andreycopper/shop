<?php

namespace Models;

use System\Validation;
use Exceptions\DbException;

class CallBack extends Model
{
    protected static $table = 'callbacks';
    public $id;        // id
    public $user_id;   // id пользователя
    public $user_hash; // хэш пользователя
    public $name;      // имя
    public $phone;     // телефон
    public $created;   // дата создания
    public $updated;   // дата изменения

    /**
     * Проверка персональных данных
     * @param array $form - форма с данными (имя и телефон)
     * @return bool
     */
    public static function checkData(array $form)
    {
        if (!empty(trim($form['agreement'])))
            if (!empty(trim($form['name'])) && Validation::name(trim($form['name'])))
                if (!empty(trim($form['phone'])) && Validation::phone(trim($form['phone'])))
                    return true;

        return false;
    }

    /**
     * Сохранение быстрого заказа
     * @param $user - пользователь
     * @param $form - форма с данными (имя и телефон)
     * @return bool
     * @throws DbException
     */
    public static function saveCallback($user, $form)
    {
        $callback = new self();
        $callback->user_id = $user->id;
        $callback->user_hash = $_COOKIE['user'];
        $callback->name = $form['name'];
        $callback->phone = preg_replace('/[^0-9]/', '', $form['phone']);
        $callback->created = date('Y-m-d H:i:s');
        return $callback->save();
    }
}
