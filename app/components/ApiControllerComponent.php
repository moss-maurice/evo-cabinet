<?php

namespace mmaurice\cabinet\components;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\controllers\ApiControllerPrototype;
use mmaurice\cabinet\models\WebUsersModel;

class ApiControllerComponent extends ApiControllerPrototype
{
    protected function checkAuth($fields = [])
    {
        if (!$this->checkAuthStatus()) {
            return $this->render($fields, self::CODE_ERROR_INPUT_FIELDS, self::STATUS_ERROR, 'Пользователь не авторизирован!');
        }

        return true;
    }

    protected function checkAuthStatus()
    {
        global $modx;

        if (WebUsersModel::model()->isLogged()) {
            return true;
        }

        return false;
    }
}
