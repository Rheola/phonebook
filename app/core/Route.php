<?php

namespace core;


/**
 * Class Route
 *
 * @package motor\core
 */
class Route
{

    /**
     * @return mixed
     */
    public static function start()
    {
        $controllerName = 'Site';
        $actionName = 'index';

        $tail = explode('?', $_SERVER['REQUEST_URI']);

        $routes = explode('/', $tail[0]);

        if (!empty($routes[1])) {
            $controllerName = $routes[1];
        }

        if (!empty($routes[2])) {
            $actionName = $routes[2];
            if (strpos($actionName, '-') !== false) {
                $z = explode('-', $actionName);
                $z = array_map('ucfirst', $z);
                $actionName = implode($z);
            }
        }

        if ($controllerName == 'index.php') {
            header('Location: /', true, 302);
        }

        $controllerName = ucfirst($controllerName) . 'Controller';

        $path = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (realpath($path)) {

            $actionName = 'action' . ucfirst($actionName);

            $c = 'controllers\\' . $controllerName;

            $controller = new $c;


            $action = $actionName;

            $path = $_SERVER['REQUEST_URI'];

            $path = rtrim($path, '/');

            // controller/action/id
            $re = '/^\/(\D*)\/(.\D*)\/(\w*)$/';
            preg_match($re, $path, $matches, PREG_OFFSET_CAPTURE);
            $params = [];

            if (isset($matches[3][0])) {
                $params = $matches[3][0];
            }

            if (method_exists($controller, $action)) {
                return $controller->$action($params);
            }
        }
        header("HTTP/1.0 404 Not Found");
        Controller::error404();
    }
}
