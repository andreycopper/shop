<?php

spl_autoload_register(
    function ($class){
        //if (0 === strpos($class, 'App')){
            //$name = str_replace('\\', '/', substr($class, 4));
            $name = str_replace('\\', '/', $class);
            $file = _APP . '/' . $name . '.php';

            if (file_exists($file)) require_once $file;
        //}
    }
);
