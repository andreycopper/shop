<?php

namespace Models\User;

use Models\Model;

class UserGroup extends Model
{
    protected static $table = 'user_groups';

    const ADMINISTRATOR = 'Администраторы';
    const USERS = 'Пользователи';
    const WHOLESALE = 'Оптовый покупатель';
    const RETAIL = 'Розничный покупатель';
}
