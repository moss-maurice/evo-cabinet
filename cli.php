<?php

// use: php cli.php controller/action param1=value1 param2=value2 ...

use mmaurice\cabinet\configs\MainConfig;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\exceptions\CommandException;

setlocale(LC_ALL, 'ru_RU.utf8');

$_SERVER['REQUEST_SCHEME'] = 'http';
$_SERVER['SERVER_NAME'] = 'host.local';
$_SERVER['REMOTE_ADDR'] = $_SERVER['SERVER_NAME'];
$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__) . '/../');

require_once realpath(dirname(__FILE__) . '/vendor/autoload.php');

ini_set('memory_limit', '4096M');

try {
    $app = App::init();
    $app->setConfig(new MainConfig);

    if ($app->isCliMode()) {
        if ($app->runConsoleHandler()) {
            $app->end(0);
        }

        throw new CommandException('Command not found!');
    }

    throw new CommandException('Access denied!');
} catch (CommandException $exception) {
    $exception->makeResponce();
}
