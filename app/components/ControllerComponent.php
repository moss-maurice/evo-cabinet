<?php

namespace mmaurice\cabinet\components;

use mmaurice\cabinet\core\App;
use mmaurice\cabinet\core\prototypes\controllers\ControllerPrototype;
use mmaurice\cabinet\models\WebUsersModel;

class ControllerComponent extends ControllerPrototype
{
    /**
     * Метод проверки доступа
     *
     * @return void
     */
    protected function checkAccess($redirect = '/')
    {
        if (!WebUsersModel::model()->isLogged()) {
            App::init()->redirect($redirect);
        }

        return false;
    }

    protected function checkManagerAccess()
    {
        if (array_key_exists('mgrValidated', $_SESSION) or intval($_SESSION['mgrValidated']) == 1) {
            return true;
        }

        return false;
    }

    protected function checkRole($roles, $redirect = '/')
    {
        if (!is_array($roles)) {
            $roles = array($roles);
        }

        if (is_array($roles) and !empty($roles)) {
            if (!in_array(WebUsersModel::model()->getRole(), $roles) and !in_array('all', $roles)) {
                App::init()->redirect($redirect);
            }
        }

        return false;
    }
}
