<?php

namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\widgets\WidgetPrototype;
use mmaurice\cabinet\models\WebUserThreadsModel;

class ThreadLineWidget extends WidgetPrototype
{
    public $template = 'module';
    public $orderId;

    public function run()
    {
        if (!is_null($this->orderId)) {
            return $this->render($this->template, [
                'orderId' => $this->orderId,
                'threadModel' => WebUserThreadsModel::model(),
            ]);
        } else {
            return $this->render('index', [
                'threadModel' => WebUserThreadsModel::model(),
            ]);
        }
    }
}
