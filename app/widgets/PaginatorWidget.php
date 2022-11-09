<?php
namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\widgets\WidgetPrototype;

class PaginatorWidget extends WidgetPrototype
{
    protected $container = '<div>[content]</div>';
    protected $firstButton = '<span rel-page="1">First</span>';
    protected $lastButton = '<span rel-page="[pages]">Last</span>';
    protected $prevButton = '<span rel-page="[prevPage]">Prev</span>';
    protected $nextButton = '<span rel-page="[nextPage]">Next</span>';
    protected $dotsButton = '<span>...</span>';
    protected $activeButton = '<span class="active" rel-page="[currentPage]">[currentPage]</span>';
    protected $button = '<span rel-page="[page]">[page]</span>';

    protected function getSetting()
    {
        return [
            'container' => $this->container,
            'firstButton' => $this->firstButton,
            'lastButton' => $this->lastButton,
            'prevButton' => $this->prevButton,
            'nextButton' => $this->nextButton,
            'dotsButton' => $this->dotsButton,
            'activeButton' => $this->activeButton,
            'button' => $this->button,
            'allButton' => $this->allButton,
            'all' => $this->all,
        ];
    }

    public function run($page = 1, $pages = 1, $range = 5)
    {
        return $this->render('form', array_merge([
            'page' => $page,
            'pages' => $pages,
            'range' => $range,
        ], $this->getSetting()));
    }
}
