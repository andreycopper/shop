<?php

namespace Models;

use System\Validation;

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
        if (!empty($form['agreement']))
            if (!empty($form['name']) && Validation::name(trim($form['name'])))
                if (!empty($form['phone']) && Validation::phone(trim($form['phone'])))
                    return true;

        return false;
    }

    /**
     * Сохранение быстрого заказа
     * @param $user_id - id пользователя
     * @param $form - форма с данными (имя и телефон)
     * @return bool
     */
    public static function saveCallback($user_id, $form)
    {
        $callback = new self();
        $callback->user_id = $user_id;
        $callback->user_hash = $_COOKIE['user'];
        $callback->name = trim($form['name']);
        $callback->phone = preg_replace('/[^0-9]/', '', trim($form['phone']));
        return $callback->save();
    }
}
