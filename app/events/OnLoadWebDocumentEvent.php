<?php

namespace mmaurice\cabinet\events;

use Bramus\Router\Router;
use mmaurice\cabinet\configs\RoutesConfig;
use mmaurice\cabinet\core\classes\AppClass;
use mmaurice\cabinet\core\classes\RequestClass;
use mmaurice\cabinet\events\OnPageNotFoundEvent;

/**
 * Событие OnLoadWebDocumentEvent
 * 
 * Срабатывает при попытке обращения к существующей странице.
 * Для рендера личного кабинета в окружении шаблона сайта, необходимо перехватывать одну из страниц сайта. Как правило
 *  это специально созданная страница. Например, lk. Поэтому тут необходимо определить, является ли существующая
 *  страница хэндлером ЛК.
 */
class OnLoadWebDocumentEvent extends OnPageNotFoundEvent
{
    public function __construct(AppClass $app)
    {
        parent::__construct($app);

        $this->initMatchRoutes($app);
    }

    protected function initMatchRoutes(AppClass $app)
    {
        global $modx;

        $handlePage = !is_null($app->getConfig('handlePage')) ? intval($app->getConfig('handlePage')) : null;

        if ($handlePage === intval($modx->documentIdentifier)) {
            parent::initMatchRoutes($app);
        }
    }
}
