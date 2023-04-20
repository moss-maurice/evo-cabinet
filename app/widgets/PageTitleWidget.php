<?php
namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\widgets\WidgetPrototype;

class PageTitleWidget extends WidgetPrototype
{
    protected $title = false;

    protected function getSetting()
    {
        return [
            'title' => $this->title,
        ];
    }

    public function run()
    {
        return $this->render('index', [
            'title' => $this->title,
        ]);
    }
}
