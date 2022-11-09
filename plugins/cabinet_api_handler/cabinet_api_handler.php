<?php

use mmaurice\cabinet\configs\MainConfig;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\exceptions\AjaxException;

setlocale(LC_ALL, 'ru_RU.utf8');

require_once realpath(dirname(__FILE__) . '/../../vendor/autoload.php');

try {
    $app = App::init();
    $app->setConfig(new MainConfig);

    if ($app::request()->isAjaxRequest()) {
        if ($app->runAjaxHandler()) {
            $app->end();
        }
    }

    throw new AjaxException('Access denied!');
} catch (AjaxException $exception) {
    $exception->makeResponce();
}
