<?php


namespace System;


class Validation
{
    const numbers = '/^\d$/i';
    const phone = '/^\+?[78]?[ -]?[(]?9\d{2}\)?[ -]?\d{3}-?\d{2}-?\d{2}$/i';
    const email = '/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,6}$/i';
    const password = '/((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,})/';
    const rus = '/^[а-яё\-" ]+$/miu';
    const rus_eng = '/^[a-zа-яё\-" ]+$/miu';
    const rus_eng_num = '/^[a-zа-яё\-" \d]+$/miu';

    public static function phone(string $phone)
    {
        return !empty($phone) && preg_match(self::phone, $phone);
    }

    public static function email(string $email)
    {
        return !empty($email) && preg_match(self::email, $email);
    }

    public static function password(string $password)
    {
        return !empty($password) && preg_match(self::password, $password);
    }

    public static function name(string $name)
    {
        return !empty($name) && preg_match(self::rus_eng, $name);
    }

    public static function numbers(string $name, $count = null)
    {
        $n = $count ? ('{' . $count . '}') : '+';//var_dump($count);var_dump($n);die;
        return !empty($name) && preg_match("/^\d{$n}$/i", $name);
    }
}
