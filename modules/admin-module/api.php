<?php

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\exceptions\AjaxException;

setlocale(LC_ALL, 'ru_RU.utf8');

require_once realpath(dirname(__FILE__) . '/../../vendor/autoload.php');

if (!defined('LK_DEBUG')) {
    define('LK_DEBUG', true);
}

$app = App::init();

if ($app::request()->isAjaxRequest()) {
    header('Content-type: application/json; charset=utf-8');

    $className = $_REQUEST['tabName'] . 'Class';
    $classPath = dirname(__FILE__) . '/tabs/' . $_REQUEST['tabName'] . '/' . $className . '.php';

    require_once realpath(dirname(__FILE__) . '/classes/tabClass.php');
    require_once realpath($classPath);

    $tabClass = new $className($_REQUEST);
    $method = 'action' . ucfirst(trim($_REQUEST['method']));

    if (method_exists($tabClass, $method)) {
        echo json_encode(call_user_func_array([$tabClass, $method], array()));
    } else {
        echo json_encode(call_user_func_array([$tabClass, 'actionIndex'], array()));
    }

    $app->end();
}
