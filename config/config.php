<?php

return [
    'db' => [
        'host'     => 'localhost',
        'dbname'   => 'shop',
        'user'     => 'root',
        'password' => 'root'
    ],
    'log' => [
        'error'    => __DIR__ . '/../logs/' . date('Y-m-d') . '_errors.log',
        'access'    => __DIR__ . '/../logs/' . date('Y-m-d') . '_access.log'
    ],
    'image' => [
        'images'     => __DIR__ . '/../public/images/',
        'uploads'     => __DIR__ . '/../public/uploads/'
    ]
];
