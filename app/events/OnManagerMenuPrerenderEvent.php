<?php

namespace mmaurice\cabinet\events;

use Ahc\Env\Loader;
use mmaurice\cabinet\core\classes\AppClass;
use mmaurice\cabinet\core\prototypes\EventPrototype;
use mmaurice\cabinet\core\providers\ModxProvider;
use mmaurice\cabinet\models\SiteModules;

/**
 * Событие OnManagerMenuPrerenderEvent
 * 
 * Срабатывает при попытке отрисовке главного меню панели администратора.
 * Тут можно реализовать вывод пользовательского пункта меню в панели администратора, ведущего на кастомный модуль.
 */
class OnManagerMenuPrerenderEvent extends EventPrototype
{
    public function __construct(AppClass $app)
    {
        parent::__construct($app);

        $this->initModuleTab();
    }

    protected function initModuleTab()
    {
        //Добавить модуль в общую навигацию
        ModxProvider::modxInit();
        $modx = ModxProvider::getModx();

        //$_customlang = include MODX_BASE_PATH . 'assets/modules/clientsettings/lang.php';
        //$userlang = $modx->getConfig('manager_language');
        //$langitem = isset($_customlang[$userlang]) ? $_customlang[$userlang] : reset($_customlang);

        $moduleId = env('MODULE_MAIN');

        $module = SiteModules::model()->getItem([
            'where' => [
                "t.id = '{$moduleId}'",
            ],
        ]);

        if ($module) {
            $modx->event->output(serialize(array_merge($modx->event->params['menu'], [
                'admin-module' => [
                    'admin-module',
                    'main',
                    "<i class=\"fa fa-cog\"></i>{$module['name']}",
                    "index.php?a=112&id={$module['id']}",
                    $module['name'],
                    '',
                    '',
                    'main',
                    0,
                    100,
                    '',
                ],
            ])));
        }
    }
}
