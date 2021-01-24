<?php

namespace App\Controllers;

use App\Models\Test;
use App\Models\User;
use App\System\RSA;

class Tests extends Controller
{
    protected function before()
    {
    }

    protected function actionDefault()
    {
        Test::fias();
        die;
    }

    protected function actionUser()
    {
        var_dump($_SESSION['user']);

        $rsa  = new RSA($_SESSION['user']['private_key']);

        var_dump($rsa->decrypt($_SESSION['user']['last_name']));
        var_dump($rsa->decrypt($_SESSION['user']['name']));
        var_dump($rsa->decrypt($_SESSION['user']['second_name']));

    }

    protected function actionRsa()
    {
        $name1 = 'Тестов';
        $name2 = 'Тест';
        $name3 = 'Тестович';
        var_dump($name1);
        var_dump($name2);
        var_dump($name3);

        $private_key = RSA::generateRandomBytes(0, true);
        $public_key = RSA::generateRandomBytes(16, true);
//        var_dump($private_key);
//        var_dump($public_key);die;

        $private_key = 'Xp1SqoKAsWB199IMslr/2w=='; // 24 - passphrase
        $public_key = $_SESSION['public_key'] = 'jfmkXaSV/IZaVhYhg7Jz4g='; // 16 - iv

        $rsa  = new RSA($private_key);
        $encode1 = $rsa->encrypt($name1);
        $encode2 = $rsa->encrypt($name2);
        $encode3 = $rsa->encrypt($name3);
        var_dump($encode1);
        var_dump($encode2);
        var_dump($encode3);

        $decode1 = $rsa->decrypt($encode1);
        $decode2 = $rsa->decrypt($encode2);
        $decode3 = $rsa->decrypt($encode3);
        var_dump($decode1);
        var_dump($decode2);
        var_dump($decode3);
    }
}