<?php

namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\widgets\WidgetPrototype;

class LeftMenuWidget extends WidgetPrototype
{
    public $menu = [];

    public function run()
    {
        return $this->render('index', [
            'menu' => $this->menu,
        ]);
    }
}
