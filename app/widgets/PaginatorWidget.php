<?php
namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\widgets\WidgetPrototype;

class PaginatorWidget extends WidgetPrototype
{
    protected $container = '<div class="text-center pt-3 mt-5 border-top"><div class="btn-group">[content]</div></div>';
    protected $firstButton = '<span class="bs btn btn-sm btn-outline-dark" rel-page="1">First</span>';
    protected $lastButton = '<span class="bs btn btn-sm btn-outline-dark" rel-page="[pages]">Last</span>';
    protected $prevButton = '<span class="bs btn btn-sm btn-outline-dark" rel-page="[prevPage]">Prev</span>';
    protected $nextButton = '<span class="bs btn btn-sm btn-outline-dark" rel-page="[nextPage]">Next</span>';
    protected $dotsButton = '<span class="bs btn btn-sm btn-outline-dark">...</span>';
    protected $activeButton = '<span class="bs btn btn-sm btn-dark text-white active" rel-page="[currentPage]">[currentPage]</span>';
    protected $button = '<span class="bs btn btn-sm btn-outline-dark" rel-page="[page]">[page]</span>';

    protected function getSetting()
    {
        return [
            'container' => urldecode($this->container),
            'firstButton' => urldecode($this->firstButton),
            'lastButton' => urldecode($this->lastButton),
            'prevButton' => urldecode($this->prevButton),
            'nextButton' => urldecode($this->nextButton),
            'dotsButton' => urldecode($this->dotsButton),
            'activeButton' => urldecode($this->activeButton),
            'button' => urldecode($this->button),
            'allButton' => urldecode($this->allButton),
            'all' => urldecode($this->all),
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
