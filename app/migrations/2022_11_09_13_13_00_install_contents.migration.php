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
    protected $pluginName = 'Cabinet Web Handler';
    protected $orderPluginName = 'Make Order for Cabinet';
    protected $moduleName = 'Модуль управления';
    protected $pageName = 'Личный кабинет';

    protected $pluginEvents = [20, 91, 214, 1000];
    protected $orderPluginEvents = [92];

    public function run()
    {
        $categoryModel = CategoriesModel::model();

        $category = $categoryModel->getItem([
            'where' => [
                "t.category = '{$this->categoryName}'",
            ],
        ]);

        if (!$category) {
            $fields = array_filter([
                'category' => $this->categoryName,
            ]);

            if ($categoryModel->insert($fields)) {
                $categoryId = $categoryModel->getInsertId();

                $category = $categoryModel->getItem([
                    'where' => [
                        "t.id = '{$categoryId}'",
                    ],
                ]);
            }
        }

        if ($category) {
            $templateModel = SiteTemplatesModel::model();

            $template = $templateModel->getItem([
                'where' => [
                    "t.templatename = '{$this->templateName}'",
                ],
            ]);

            if (!$template) {
                $fields = array_filter([
                    'templatename' => $this->templateName,
                    'description' => 'Cabinet default template',
                    'category' => $category['id'],
                    'content' => "{{header}}\r\n<main class=\"b-main\">\r\n    [*content*]\r\n</main>\r\n{{footer}}",
                    'createdon' => time(),
                    'editedon' => time(),
                ]);

                if ($templateModel->insert($fields)) {
                    $templateId = $templateModel->getInsertId();

                    $template = $templateModel->getItem([
                        'where' => [
                            "t.id = '{$templateId}'",
                        ],
                    ]);
                }
            }

            if ($template) {
                $pluginModel = SitePlugins::model();

                $plugin = $pluginModel->getItem([
                    'where' => [
                        "t.name = '{$this->pluginName}'",
                    ],
                ]);

                if (!$plugin) {
                    $fields = array_filter([
                        'name' => $this->pluginName,
                        'description' => '<strong>0.1</strong> Private User Cabinet web-frontend for ModX Evolution 1.4.x',
                        'category' => $category['id'],
                        'plugincode' => "require_once realpath(MODX_BASE_PATH . \\'/cabinet/plugins/cabinet_web_handler/cabinet_web_handler.php\\');\r\n",
                        'createdon' => time(),
                        'editedon' => time(),
                    ]);

                    if ($pluginModel->insert($fields)) {
                        $pluginId = $pluginModel->getInsertId();

                        $plugin = $pluginModel->getItem([
                            'where' => [
                                "t.id = '{$pluginId}'",
                            ],
                        ]);
                    }
                }

                if ($plugin) {
                    if (is_array($this->pluginEvents) and !empty($this->pluginEvents)) {
                        $pluginEventModel = SitePluginsEvents::model();

                        foreach ($this->pluginEvents as $eventId) {
                            $fields = array_filter([
                                'pluginid' => $plugin['id'],
                                'evtid' => $eventId,
                            ]);

                            if ($pluginEventModel->insert($fields)) {
                                // do nothing!
                            }
                        }
                    }
                }

                $plugin = $pluginModel->getItem([
                    'where' => [
                        "t.name = '{$this->orderPluginName}'",
                    ],
                ]);

                if (!$plugin) {
                    $fields = array_filter([
                        'name' => $this->orderPluginName,
                        'description' => '<strong>0.1</strong> Order form for Private User Cabinet',
                        'category' => $category['id'],
                        'plugincode' => "require_once realpath(MODX_BASE_PATH . \\'/cabinet/plugins/make_order/make_order.php\\');\r\n",
                        'createdon' => time(),
                        'editedon' => time(),
                    ]);

                    if ($pluginModel->insert($fields)) {
                        $pluginId = $pluginModel->getInsertId();

                        $plugin = $pluginModel->getItem([
                            'where' => [
                                "t.id = '{$pluginId}'",
                            ],
                        ]);
                    }
                }

                if ($plugin) {
                    if (is_array($this->orderPluginEvents) and !empty($this->orderPluginEvents)) {
                        $pluginEventModel = SitePluginsEvents::model();

                        foreach ($this->orderPluginEvents as $eventId) {
                            $fields = array_filter([
                                'pluginid' => $plugin['id'],
                                'evtid' => $eventId,
                            ]);

                            if ($pluginEventModel->insert($fields)) {
                                // do nothing!
                            }
                        }
                    }
                }

                $moduleModel = SiteModules::model();

                $module = $moduleModel->getItem([
                    'where' => [
                        "t.name = '{$this->moduleName}'",
                    ],
                ]);

                if (!$module) {
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

                    if ($moduleModel->insert($fields)) {
                        $moduleId = $moduleModel->getInsertId();

                        $module = $moduleModel->getItem([
                            'where' => [
                                "t.id = '{$moduleId}'",
                            ],
                        ]);
                    }
                }

                if ($module) {
                    $pageModel = SiteContentModel::model();

                    $page = $pageModel->getItem([
                        'where' => [
                            "t.pagetitle = '{$this->pageName}'",
                        ],
                    ]);

                    if (!$page) {
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

                        if ($pageModel->insert($fields)) {
                            $pageId = $pageModel->getInsertId();

                            $page = $pageModel->getItem([
                                'where' => [
                                    "t.id = '{$pageId}'",
                                ],
                            ]);
                        }
                    }

                    if ($page) {
                        file_put_contents(realpath(dirname(__FILE__) . '/../../') . '/.env', "HANDLE_PAGE={$page['id']}\r\nLOGGER_LEVEL=0\r\nMODULE_MAIN={$module['id']}\r\n");

                        echo "  >>> Autoconfigure ENV-file placed in '" . realpath(dirname(__FILE__) . '/../../.env') . "'!" . PHP_EOL;

                        return true;
                    }
                }
            }
        }

        return false;
    }
}
