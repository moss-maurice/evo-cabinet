<?php

use mmaurice\cabinet\core\helpers\CmdHelper;
use mmaurice\cabinet\core\prototypes\MigrationPrototype;
use mmaurice\cabinet\models\CategoriesModel;
use mmaurice\cabinet\models\SiteContentModel;
use mmaurice\cabinet\models\SiteModules;
use mmaurice\cabinet\models\SitePlugins;
use mmaurice\cabinet\models\SitePluginsEvents;
use mmaurice\cabinet\models\SiteTemplatesModel;

class migration_2022_11_09_13_13_00_install_contents extends MigrationPrototype
{
    protected $categoryName = 'Cabinet';
    protected $templateName = 'Cabinet Template';
    protected $moduleName = 'Модуль управления';
    protected $pageName = 'Личный кабинет';
    protected $plugins = [
        [
            'name' => 'Cabinet Web Handler',
            'events' => [20, 91, 214, 1000],
            'description' => '<strong>0.1</strong> Private User Cabinet web-frontend for ModX Evolution 1.4.x',
            'code' => "require_once realpath(MODX_BASE_PATH . \\'/cabinet/plugins/cabinet_web_handler/cabinet_web_handler.php\\');\r\n",
        ],
        [
            'name' => 'Make Order for Cabinet',
            'events' => [92],
            'description' => '<strong>0.1</strong> Order form for Private User Cabinet',
            'code' => "require_once realpath(MODX_BASE_PATH . \\'/cabinet/plugins/make_order/make_order.php\\');\r\n",
        ],
        [
            'name' => 'Make Auth for Cabinet',
            'events' => [92],
            'description' => '<strong>0.1</strong> Auth form for Private User Cabinet',
            'code' => "require_once realpath(MODX_BASE_PATH . \\'/cabinet/plugins/make_auth/make_auth.php\\');\r\n",
        ],
    ];

    public function run()
    {
        $category = $this->makeCategory();

        if ($category) {
            $template = $this->makeTemplate($category);

            if ($template) {
                $this->makePlugins($category);
                $module = $this->makeModule($category);
                $page = $this->makePage($template);

                if ($page) {
                    return $this->makeEnv($module, $page);
                }
            }
        }

        return false;
    }

    protected function makeCategory()
    {
        $result = CategoriesModel::model()->getItem([
            'where' => [
                "t.category = '{$this->categoryName}'",
            ],
        ]);

        if (!$result) {
            $fields = array_filter([
                'category' => $this->categoryName,
            ]);

            if (CategoriesModel::model()->insert($fields)) {
                $categoryId = CategoriesModel::model()->getInsertId();

                $result = CategoriesModel::model()->getItem([
                    'where' => [
                        "t.id = '{$categoryId}'",
                    ],
                ]);
            }
        }

        return $result;
    }

    protected function makeTemplate($category)
    {
        $result = SiteTemplatesModel::model()->getItem([
            'where' => [
                "t.templatename = '{$this->templateName}'",
            ],
        ]);

        if (!$result) {
            $fields = array_filter([
                'templatename' => $this->templateName,
                'description' => 'Cabinet default template',
                'category' => $category['id'],
                'content' => "{{header}}\r\n<main class=\"b-main\">\r\n    [*content*]\r\n</main>\r\n{{footer}}",
                'createdon' => time(),
                'editedon' => time(),
            ]);

            if (SiteTemplatesModel::model()->insert($fields)) {
                $templateId = SiteTemplatesModel::model()->getInsertId();

                $result = SiteTemplatesModel::model()->getItem([
                    'where' => [
                        "t.id = '{$templateId}'",
                    ],
                ]);
            }
        }
    }

    protected function makePlugins($category)
    {
        if (is_array($this->plugins) and !empty($this->plugins)) {
            foreach ($this->plugins as $plugin) {
                $result = SitePlugins::model()->getItem([
                    'where' => [
                        "t.name = '{$plugin['name']}'",
                    ],
                ]);

                if (!$result) {
                    $fields = array_filter([
                        'name' => $plugin['name'],
                        'description' => $plugin['description'],
                        'category' => $category['id'],
                        'plugincode' => $plugin['code'],
                        'createdon' => time(),
                        'editedon' => time(),
                    ]);

                    if (SitePlugins::model()->insert($fields)) {
                        $pluginId = SitePlugins::model()->getInsertId();

                        $result = SitePlugins::model()->getItem([
                            'where' => [
                                "t.id = '{$pluginId}'",
                            ],
                        ]);
                    }
                }

                if ($result) {
                    $this->makeEvents($result, $plugin['events']);
                }
            }
        }
    }

    protected function makeEvents($plugin, array $events)
    {
        if (is_array($events) and !empty($events)) {
            foreach ($events as $event) {
                $fields = array_filter([
                    'pluginid' => $plugin['id'],
                    'evtid' => $event,
                ]);

                if (SitePluginsEvents::model()->insert($fields)) {
                    // do nothing!
                }
            }
        }
    }

    protected function makeModule($category)
    {
        $result = SiteModules::model()->getItem([
            'where' => [
                "t.name = '{$this->moduleName}'",
            ],
        ]);

        if (!$result) {
            $fields = array_filter([
                'name' => $this->moduleName,
                'description' => '<strong>0.1</strong> Private User Cabinet admin module for ModX Evolution 1.4.x',
                'category' => $category['id'],
                'createdon' => time(),
                'editedon' => time(),
                'guid' => '1205f5fe31dbb0d6cd2d16394b8f2e53',
                'properties' => '{}',
                'modulecode' => "require_once realpath(MODX_BASE_PATH . \\'/cabinet/modules/admin-module/admin-module.php\\');\r\n",
            ]);

            if (SiteModules::model()->insert($fields)) {
                $moduleId = SiteModules::model()->getInsertId();

                $result = SiteModules::model()->getItem([
                    'where' => [
                        "t.id = '{$moduleId}'",
                    ],
                ]);
            }
        }

        return $result;
    }

    protected function makePage($template)
    {
        $result = SiteContentModel::model()->getItem([
            'where' => [
                "t.pagetitle = '{$this->pageName}'",
            ],
        ]);

        if (!$result) {
            $fields = array_filter([
                'pagetitle' => $this->pageName,
                'alias' => 'lk',
                'hidemenu' => 1,
                'published' => 1,
                'template' => $template['id'],
                'searchable' => 0,
                'cacheable' => 0,
                'createdby' => 1,
                'createdon' => time(),
                'editedby' => 1,
                'editedon' => time(),
                'publishedby' => 1,
                'publishedon' => time(),
            ]);

            if (SiteContentModel::model()->insert($fields)) {
                $pageId = SiteContentModel::model()->getInsertId();

                $result = SiteContentModel::model()->getItem([
                    'where' => [
                        "t.id = '{$pageId}'",
                    ],
                ]);
            }
        }

        return $result;
    }

    protected function makeEnv($module, $page)
    {
        $envFilePath = realpath(dirname(__FILE__) . '/../../') . DIRECTORY_SEPARATOR . '.env';

        $envBody = "HANDLE_PAGE={$page['id']}\r\nLOGGER_LEVEL=0\r\nMODULE_MAIN={$module['id']}\r\n";

        file_put_contents($envFilePath, $envBody);

        if (realpath($envFilePath)) {
            echo "  >>> Autoconfigure ENV-file placed in '{$envFilePath}'!" . PHP_EOL;

            return true;
        }

        return false;
    }
}
