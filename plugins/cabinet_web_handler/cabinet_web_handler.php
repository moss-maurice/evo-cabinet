<?php

use mmaurice\cabinet\configs\MainConfig;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\exceptions\WebExceptions;

setlocale(LC_ALL, 'ru_RU.utf8');

require_once realpath(dirname(__FILE__) . '/../../vendor/autoload.php');

try {
    if (!defined('LK_DEBUG')) {
        define('LK_DEBUG', true);
    }

    if (defined('LK_DEBUG') and (LK_DEBUG === true)) {
        $_SESSION['db_query_log'] = [];
    } else {
        unset($_SESSION['db_query_log']);
    }

    $app = App::init();
    $app->setConfig(new MainConfig);

    $app->runWebHandler();
} catch (WebExceptions $exceprions) {
    $exception->makeResponce();
}
