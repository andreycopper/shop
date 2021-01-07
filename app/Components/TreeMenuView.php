<?php

namespace App\Components;

use App\Views\View;

class TreeMenuView
{
    protected $data;

    protected $shift;

    protected $parent_id;

    public function __construct($data, $parent_id = 0, int $shift = 0)
    {
        $this->data = $data;
        $this->shift = $shift;
        $this->parent_id = $parent_id;
    }

    public function display($path)
    {
        $view = new View();
        $view->menu  = $this->data;
        $view->parent_id = $this->parent_id;
        $view->shift       = $this->shift;
        $view->display($path);
    }
}
