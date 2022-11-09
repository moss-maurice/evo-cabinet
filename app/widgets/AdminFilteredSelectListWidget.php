<?php

namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\widgets\WidgetPrototype;

/**
 * echo AdminFilteredSelectListWidget::init()->run([
 *      [
 *          'id' => 1,
 *          'value' => 'foo',
 *          'active' => true,
 *      ],
 *      [
 *          'id' => 2,
 *          'value' => 'bar',
 *      ],
 *      [
 *          'id' => 3,
 *          'value' => 'baz',
 *      ]
 *  ]);
 */

class AdminFilteredSelectListWidget extends WidgetPrototype
{
    const WIDGET_PATH = '/widgets/views/filteredSelectList';
    const WIDGET_THEMES = [
        'bootstrap2' => '/assets/libs/selectize-0.13.3/css/selectize.bootstrap2.css',
        'bootstrap3' => '/assets/libs/selectize-0.13.3/css/selectize.bootstrap3.css',
        'bootstrap4' => '/assets/libs/selectize-0.13.3/css/selectize.bootstrap4.css',
        'default' => '/assets/libs/selectize-0.13.3/css/selectize.default.css',
        'legacy' => '/assets/libs/selectize-0.13.3/css/selectize.legacy.css',
        'modx' => '/assets/libs/selectize-0.13.3/css/selectize.modx.css',
        'selectize' => '/assets/libs/selectize-0.13.3/css/selectize.css',
    ];
    const WIDGET_THEME_DEFAULT = 'modx';

    protected $id;
    protected $class;
    protected $name;
    protected $optionTmpl;
    protected $itemTmpl;
    protected $valueField = 'id';
    protected $searchField = 'value';
    protected $theme;

    public function __construct($parametrs = [])
    {
        parent::__construct($parametrs);

        if (!is_array($this->class)) {
            if (is_null($this->class)) {
                $this->class = [];
            }

            $this->class = array($this->class);

            if (!is_null($this->name) and !empty($this->name)) {
                $this->class = array_merge($this->class, [$this->name]);
            }
        }

        $this->id = (!is_null($this->id) ? $this->id : uniqid());
        $this->class = (!empty($this->class) ? implode(' ', $this->class) : '');
        $this->optionTmpl = $this->prepareTmpl('option', $this->optionTmpl);
        $this->itemTmpl = $this->prepareTmpl('item', $this->itemTmpl);
        $this->theme = (!is_null($this->theme) ? (array_key_exists($this->theme, self::WIDGET_THEMES) ? $this->theme : self::WIDGET_THEME_DEFAULT) : self::WIDGET_THEME_DEFAULT);
    }

    protected function prepareTmpl($tmpl, $value = null)
    {
        $content = (!is_null($value) ? $value : App::response()->renderTemplate(App::getPublicRoot(self::WIDGET_PATH . "/tmpl/{$tmpl}.php"), $this->settings()));

        return str_replace(PHP_EOL, '', $content);
    }

    protected function settings()
    {
        return [
            'id' => $this->id,
            'class' => $this->class,
            'name' => $this->name,
            'optionTmpl' => $this->optionTmpl,
            'itemTmpl' => $this->itemTmpl,
            'valueField' => $this->valueField,
            'searchField' => $this->searchField,
            'theme' => $this->theme,
        ];
    }

    protected function render($templateName, $parametrs = [])
    {
        echo App::response()->renderTemplate(App::getPublicRoot() . self::WIDGET_PATH . '/' . $templateName . '.php', $parametrs);
    }

    public function run($rawOptions = [])
    {
        $selected = [];

        $options = array_map(function ($value) use (&$selected) {
            if (array_key_exists('active', $value)) {
                if ($value['active']) {
                    $selected[] = intval($value['id']);
                }

                unset($value['active']);
            }

            return $value;
        }, $rawOptions);

        usort($options, function ($left, $right) {
            return (($left['value'] <= $right['value']) ? (($left['value'] < $right['value']) ? -1 : 0) : 1);
        });

        return $this->render('index', array_merge([
            'selected' => json_encode($selected),
            'options' => json_encode($options),
        ], $this->settings()));
    }
}
