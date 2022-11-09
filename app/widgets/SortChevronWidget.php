<?php

namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\widgets\WidgetPrototype;

class SortChevronWidget extends WidgetPrototype
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';

    protected $page;
    protected $tab;
    protected $method;
    protected $field;
    protected $direction;
    protected $active;

    protected function getSetting()
    {
        return [
            'page' => $this->page,
            'tab' => $this->tab,
            'method' => $this->method,
            'currentField' => $this->field,
            'currentDirection' => (in_array(strtoupper(trim($this->direction)), [self::DIRECTION_ASC, self::DIRECTION_DESC]) ? strtoupper(trim($this->direction)) : self::DIRECTION_ASC),
            'active' => $this->active,
        ];
    }

    public function up($field)
    {
        return $this->render('manual-form', array_merge([
            'field' => $field,
            'direction' => self::DIRECTION_DESC,
        ], $this->getSetting()));
    }

    public function down($field)
    {
        return $this->render('manual-form', array_merge([
            'field' => $field,
            'direction' => self::DIRECTION_ASC,
        ], $this->getSetting()));
    }

    public function draw($field)
    {
        $direction = self::DIRECTION_DESC;

        if ($this->direction == $direction) {
            $direction = self::DIRECTION_ASC;
        }

        return $this->render('form', array_merge([
            'field' => $field,
            'direction' => $direction,
        ], $this->getSetting()));
    }
}
