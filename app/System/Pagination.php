<?php

namespace System;

class Pagination
{
    public static function make($items, $page_count)
    {
        if (!empty($items) && is_array($items)) {
            $res = [];
            $page = 1;
            $i = 1;

            foreach ($items as $key => $item) {
                $res['pages'][$page] = $page;
                $res[$page][] = $item;
                if ($i % $page_count === 0) $page++;
                $i++;
            }
        }

        return $res ?? $items;
    }
}
