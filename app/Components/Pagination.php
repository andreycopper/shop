<?php

namespace App\Components;

class Pagination
{
    public static function make(int $current, int $count, int $itemsLeft = 3, int $itemsRight = 3)
    {
        $data = [];

        $items = $itemsLeft + $itemsRight + 1;

        if ($count <= $items) {
            for ($i = 1; $i <= $count; $i++) {
                $data[] = $i;
            }
        } else {
            if ($current <= $itemsLeft + 1) {
                for ($i = 1; $i <= $items; $i++) {
                    $data[] = $i;
                }
            } elseif ($current >= $count - $itemsRight) {
                for ($i = $count - ($itemsLeft + $itemsRight); $i <= $count; $i++) {
                    $data[] = $i;
                }
            } else {
                for ($i = $current - $itemsLeft; $i <= $current + $itemsRight; $i++) {
                    $data[] = $i;
                }
            }
        }
        return $data;
    }
}
