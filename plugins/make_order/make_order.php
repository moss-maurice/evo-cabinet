<?php

use mmaurice\cabinet\configs\MainConfig;
use mmaurice\cabinet\core\App;
use mmaurice\cabinet\models\WebUsersModel;

require_once __DIR__ . '/make_oder.class.php';
require_once realpath(dirname(__FILE__) . '/../../vendor/autoload.php');

$app = App::init();
$app->setConfig(new MainConfig);

if (WebUsersModel::model()->isLogged()) {
    MakeOrderPlugin::drawForm();
} else {
    MakeOrderPlugin::drawAuth();
}
