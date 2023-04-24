<?php

namespace mmaurice\cabinet\configs;

use mmaurice\cabinet\core\prototypes\RoutesConfigPrototype;

/**
 * Файл конфигурации
 */
class RoutesConfig implements RoutesConfigPrototype
{
    public $acceptedLangs = [
        'ru',
    ];

    public $sourceIndex = [
        '/{lk}' => 'main/index',
    ];

    public $source = [
        // General
        '/{lk}/orders' => 'orders/index',
        '/{lk}/order' => 'orders/view',
        '/{lk}/order/message' => 'orders/addMessage',
        '/{lk}/profile' => 'profile/index',
        '/{lk}/profile/update' => 'profile/update',

        // Auth
        '/{lk}/login' => 'auth/login',
        '/{lk}/login/master' => 'auth/master',
        '/{lk}/login/remind' => 'auth/remindPassword',
        '/{lk}/login/check-key' => 'auth/restore',
        '/{lk}/register' => 'auth/register',
        '/{lk}/logout' => 'auth/logout',

        // Api Auth
        '/{lk}/api/auth/login' => 'api/auth/login',
        '/{lk}/api/auth/remind' => 'api/auth/remindPassword',
        '/{lk}/api/auth/register' => 'api/auth/register',
        '/{lk}/api/auth/check-code' => 'api/auth/checkCode',

        // API
        '/{lk}/api/order' => 'api/order/index',
    ];
}
