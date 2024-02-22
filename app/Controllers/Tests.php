<?php

namespace Controllers;

use Models\User\User;
use System\RSA;
use Models\Test;
use Models\Product\Product;

class Tests extends Controller
{
    protected function before()
    {
    }

    protected function actionDefault()
    {
        $users = User::getList();var_dump($users);

//        foreach ($users as $user) {
//            $rsa  = new RSA($user['private_key']);
//
//            var_dump($rsa->decrypt($user['last_name']));
//            var_dump($rsa->decrypt($user['name']));
//            var_dump($rsa->decrypt($user['second_name']));
//        }

        die;
    }

    protected function actionCache()
    {
        file_put_contents(DIR_CACHE . '/test1', 222);
    }

    protected function actionCache3()
    {
        echo 444;
    }

    protected function actionStores()
    {
        Test::stores();
    }

    protected function actionDiscount()
    {
        var_dump(Product::getById(1));
        var_dump(Product::getByField('discount', 10));
        Test::db();die;
    }

    protected function actionActions()
    {
        Test::actions();die;
    }

    protected function actionQuantity()
    {
        Test::quantity();die;
    }

    protected function actionViews()
    {
        Test::views();die;
    }

    protected function actionPrice()
    {
        Test::prices();die;
    }

    protected function actionFias()
    {
        Test::fias();die;
    }

    protected function actionUser()
    {
        var_dump($_SESSION['user']);

        $rsa  = new RSA($_SESSION['user']->private_key);

        var_dump($rsa->decrypt($_SESSION['user']->last_name));
        var_dump($rsa->decrypt($_SESSION['user']->name));
        var_dump($rsa->decrypt($_SESSION['user']->second_name));

    }

    protected function actionRsa()
    {
        $name1 = 'Тестов';
        $name2 = 'Тест';
        $name3 = 'Тестович';
        var_dump($name1);
        var_dump($name2);
        var_dump($name3);

        $private_key = RSA::generateRandomBytes(64, true);
        $public_key = RSA::generateRandomBytes(16, true);
        var_dump($private_key);
        var_dump($public_key);
//        die;

//        $private_key = 'Xp1SqoKAsWB199IMslr/2w=='; // 24 - passphrase
//        $public_key = $_SESSION['public_key'] = 'jfmkXaSV/IZaVhYhg7Jz4g='; // 16 - iv

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

    protected function actionImages()
    {
        Test::images();
    }

    protected function actionProperties()
    {
        if ($handle = fopen(DIR_PUBLIC . "/files/1.csv", "r")) {
            while (($data = fgetcsv($handle, 5000, ";")) !== false) {
                var_dump($data);
            }
        }
        die;
    }
}
