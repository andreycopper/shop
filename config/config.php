<?php

return [
    'db' => [
        'host'     => 'localhost',
        'dbname'   => 'shop',
        'user'     => 'root',
        'password' => 'root'
    ],
    'log' => [
        'error'    => _LOGS . DIRECTORY_SEPARATOR . date('Y-m-d') . '_errors.log',
        'access'    => _LOGS . DIRECTORY_SEPARATOR . date('Y-m-d') . '_access.log'
    ],
    'image' => [
        'images'     => _IMAGES,
        'uploads'     => _UPLOADS
    ]
];
