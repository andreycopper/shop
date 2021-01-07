<?php

namespace App\Components;

class AjaxLoader
{
    public static function loadModal(string $template, object $item)
    {
        ob_start();
        include $template;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
