<?php

namespace mmaurice\cabinet\events;

use Bramus\Router\Router;
use mmaurice\cabinet\configs\RoutesConfig;
use mmaurice\cabinet\core\classes\AppClass;
use mmaurice\cabinet\core\classes\RequestClass;
use mmaurice\cabinet\core\prototypes\EventPrototype;

/**
 * Событие OnPageNotFoundEvent
 * 
 * Срабатывает при попытке обращения к несуществующей странице.
 */
class OnPageNotFoundEvent extends EventPrototype
{
    public function __construct(AppClass $app)
    {
        parent::__construct($app);

        $this->initMatchRoutes($app);
    }

    protected function initMatchRoutes(AppClass $app)
    {
        $routesConfig = new RoutesConfig;

        $sources = array_merge($routesConfig->source, $routesConfig->sourceIndex);

        if (is_array($sources) and !empty($sources)) {
            $router = new Router;

            $router->before('GET|POST', '.*', function () {
                header('X-Powered-By: mmaurice/evo-cabinet');
            });

            foreach ($sources as $routeMap => $routeHandle) {
                $routeHandleArray = explode('/', $routeHandle);

                if (preg_match('/^(.*)\/([^\/\^]+)$/imU', $routeHandle, $matches)) {
                    $className = $app::getControllerClassName($matches[1]);
                    $actionName = $app::getControllerActionName($matches[2]);

                    $map = $app->makeUrl($routeMap);

                    $router->before('GET|POST', $map, function() use ($className, $actionName) {
                        RequestClass::$controller = $className;
                        RequestClass::$method = $actionName;
                    });

                    $router->match('GET|POST', $map, $className . '@' . $actionName);
                }
            }

            $router->set404(function() {
                // do nothing
                return;
            });

            $router->run();
        }
    }
}
