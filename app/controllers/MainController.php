<?php

namespace mmaurice\cabinet\controllers;

use mmaurice\cabinet\components\ControllerComponent;
use mmaurice\cabinet\core\App;

class MainController extends ControllerComponent
{
    public function actionIndex()
    {
        $this->checkAccess('/{lk}/login/');
        $this->checkRole('all', '/login');
        App::init()->redirect('/{lk}/orders');
        $this->render('index');
    }
}
