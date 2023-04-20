<?php

use mmaurice\cabinet\core\App;

$content = '';

if ($page > 0) {
    if ($page > 1) {
        $prevPage = ((($page - 1) >= 1) ? ($page - 1) : $page);

        $content .= $firstButton;
        $content .= str_replace('[prevPage]', $prevPage, $prevButton);
    }

    if ($page > ($range + 1)) {
        $content .= $dotsButton;
    }

    for ($currentPage = ($page - $range); $currentPage < $page; $currentPage++) {
        if ($currentPage >= 1) {
            $content .= str_replace('[page]', $currentPage, $button);
        }
    }

    if ($pages > 1) {
        $content .= str_replace('[currentPage]', $page, $activeButton);
    }

    for ($currentPage = ($page + 1); $currentPage < ($page + $range + 1); $currentPage++) {
        if ($currentPage <= $pages) {
            $content .= str_replace('[page]', $currentPage, $button);
        }
    }

    if ($page < ($pages - $range - 1)) {
        $content .= $dotsButton;
    }

    if ($page < $pages) {
        $nextPage = (($page + 1) <= $pages) ? ($page + 1) : $page;

        $content .= str_replace('[nextPage]', $nextPage, $nextButton);
        $content .= str_replace('[pages]', $pages, $lastButton);
    }
}

echo str_replace('[content]', $content, $container);