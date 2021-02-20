<?php

namespace System;

class Pagination
{
    public static function make($items, $perPage)
    {
        if (!empty($items) && is_array($items)) {
            $res = [];
            $page = 1;
            $i = 1;

            foreach ($items as $key => $item) {
                $res['pages'][$page] = $page;
                $res[$page][] = $item;
                if ($i % $perPage === 0) $page++;
                $i++;
            }
        }

        return $res ?? $items;
    }
}
