<?php

namespace App\Core;

class Router
{
    public function dispatch($url)
    {
        // Strip extra slashes and split the URL
        $parts = explode('/', trim($url, '/'));

        // Determine Controller (Default to HomeController)
        $controllerName = !empty($parts[0]) && $parts[0] !== 'home' ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
        $methodName = $parts[1] ?? 'index';
        $params = array_slice($parts, 2);

        $controllerFile = __DIR__ . '/../Controllers/' . $controllerName . '.php';

        // 1. Try to load the MVC Controller
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerClass = "\\App\\Controllers\\" . $controllerName;
            $controller = new $controllerClass();

            if (method_exists($controller, $methodName)) {
                call_user_func_array([$controller, $methodName], $params);
                return;
            }
        }

        // 2. Legacy Fallback (Allows your old files to keep working while we migrate)
        $legacyFile = __DIR__ . '/../../' . $url . '.php';

        // We don't want an infinite loop with index.php
        if (file_exists($legacyFile) && $url !== 'index' && $url !== '') {
            require_once $legacyFile;
            return;
        }

        // 3. 404 Not Found
        http_response_code(404);
        echo "<h1 style='text-align: center; margin-top: 50px;'>404 - Page Not Found</h1>";
    }
}
