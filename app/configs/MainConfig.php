<?php

namespace mmaurice\cabinet\configs;

use Ahc\Env\Loader;
use mmaurice\cabinet\core\classes\LoggerClass;
use mmaurice\cabinet\core\prototypes\ConfigPrototype;

/**
 * Файл конфигурации
 */
class MainConfig implements ConfigPrototype
{
    // ID страницы, созданной специально под ЛК
    public $handlePage;

    // Логгер
    // NO_MESSAGES: 0
    // ALL_MESSAGES: 1
    // IMPORTANT_MESSAGES: 2
    // ERROR_MESSAGES: 3
    public $logger = [];

    public function __construct()
    {
        $this->logger['level'] = LoggerClass::NO_MESSAGES;

        if ($envPath = realpath(dirname(__FILE__) . '/../../.env')) {
            (new Loader)->load(realpath(dirname(__FILE__) . '/../../.env'));

            $this->handlePage = env('HANDLE_PAGE', 1);
            $this->logger['level'] = env('LOGGER_LEVEL', LoggerClass::NO_MESSAGES);
        }
    }
}
