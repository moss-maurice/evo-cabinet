<?php

namespace mmaurice\cabinet\widgets;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\widgets\PaginatorWidget;

class AdminPaginatorWidget extends PaginatorWidget
{
    protected $container = '<div>[content]</div>';
    protected $firstButton = '<span class="btn first" rel-page="1">Первая</span>';
    protected $lastButton = '<span class="btn last" rel-page="[pages]">Последняя</span>';
    protected $prevButton = '<span class="btn" rel-page="[prevPage]">Назад</span>';
    protected $nextButton = '<span class="btn" rel-page="[nextPage]">Вперед</span>';
    protected $dotsButton = '<span class="page-item dots">...</span>';
    protected $activeButton = '<span class="page-item num active bg-success" rel-page="[currentPage]">[currentPage]</span>';
    protected $button = '<span class="page-item num" rel-page="[page]">[page]</span>';

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

    protected function render($templateName, $parametrs = [])
    {
        echo App::response()->renderTemplate(App::getPublicRoot() . '/widgets/views/paginator/' . $templateName . '.php', $parametrs);
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
