<?php

namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\widgets\WidgetPrototype;
use mmaurice\cabinet\models\WebUsersModel;

class ToolbarWidget extends WidgetPrototype
{
    public $menu = [];

    public function run()
    {
        return $this->render('index', [
            'menu' => serialize($this->menu),
            'first_name' => WebUsersModel::model()->getSetting('first_name', ''),
            'last_name' => WebUsersModel::model()->getSetting('last_name', ''),
        ]);
    }
}
