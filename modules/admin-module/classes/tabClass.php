<?php

class TabClass
{
    public $title = '';
    public $description = '';
    public $orderPosition = 999;

    protected $request;

    public function __construct(array $request = [])
    {
        $this->request = $request;
    }

    public function __get($property)
    {
        if ($property === 'tabName') {
            return lcfirst(preg_replace('/(.*)(Class)$/i', '$1', get_called_class()));
        }
    }

    protected function render($viewName, $properties = [])
    {
        $tabName = lcfirst(preg_replace('/(.*)(class)$/i', '$1', get_called_class()));

        $templatePath = realpath(dirname(__FILE__) . '/../tabs/' . $tabName . '/views/' . $viewName . '.php');

        return $this->renderTemplateFile($templatePath, $properties);
    }

    protected function renderJson($properties = [])
    {
        header("Content-type: application/json; charset=utf-8");

        die(json_encode($properties));
    }

    protected function renderTemplateFile($__tplName__, $__variables__ = [])
    {
        try {
            $__tplPath__ = realpath($__tplName__);

            if (!file_exists($__tplPath__) or !is_file($__tplPath__)) {
                throw new Exception('Template file "' . $__tplName__ . '" is not found!');
            }

            $__variables__['modulePath'] = '/' . ltrim(str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__) . '/..'))), '/');

            if (is_array($__variables__) and !empty($__variables__)) {
                extract($__variables__, EXTR_PREFIX_SAME, 'data');
            } else {
                $data = $__variables__;
            }

            ob_start();
            ob_implicit_flush(false);

            include($__tplPath__);

            $content = ob_get_clean();

            return $content;
        } catch (Exception $exceptiob) {
            echo $exceptiob->getMessage();
        }
    }

    public function actionIndex()
    {
        return null;
    }
}

?>