<?php

namespace App\Core\Models;

require_once dirname(__DIR__, 3) . '/config/app.php';

use App\Core\Helpers\RouteHelper;
use Exception;

class Route
{
    protected static array $routes = [];
    protected static array $routeMiddleware = []; // method:uri => middleware list
    protected static $notFound;
    protected static array $namedRoutes = [];
    protected static string $groupPrefix = '';
    protected static array $groupMiddleware = [];

    public static function get(string $uri, $handler, $data = []): RouteHelper
    {
        $uri = rtrim($uri, '/') ?: '/';

        if (self::$groupPrefix) {
            $uri = '/' . trim(self::$groupPrefix . '/' . ltrim($uri, '/'), '/');
        }

        $route = [
            'uri' => $uri,
            'handler' => $handler,
            'data' => $data,
        ];

        self::$routes['GET'][] = $route;

        // apply group middleware automatically
        if (!empty(self::$groupMiddleware)) {
            self::attachMiddleware('GET', $uri, self::$groupMiddleware);
        }

        return new RouteHelper('GET', $uri, $handler);
    }

    public static function post(string $uri, $handler, $data = []): RouteHelper
    {
        $uri = rtrim($uri, '/') ?: '/';

        if (self::$groupPrefix) {
            $uri = '/' . trim(self::$groupPrefix . '/' . ltrim($uri, '/'), '/');
        }

        $route = [
            'uri' => $uri,
            'handler' => $handler,
            'data' => $data,
        ];

        self::$routes['POST'][] = $route;

        // Apply group middleware if any
        if (!empty(self::$groupMiddleware)) {
            self::attachMiddleware('POST', $uri, self::$groupMiddleware);
        }

        return new RouteHelper('POST', $uri, $handler);
    }

    public static function group(
        string $prefix,
        array $middleware,
        callable $callback
    ): void {
        $previousPrefix = self::$groupPrefix;
        $previousMiddleware = self::$groupMiddleware;

        self::$groupPrefix = rtrim(self::$groupPrefix . '/' . trim($prefix, '/'), '/');
        self::$groupMiddleware = array_merge(self::$groupMiddleware, $middleware);

        $callback();

        self::$groupPrefix = $previousPrefix;
        self::$groupMiddleware = $previousMiddleware;
    }

    public static function attachMiddleware(string $method, string $uri, array $middleware): void
    {
        $key = "$method:$uri";
        self::$routeMiddleware[$key] = $middleware;
    }

    public static function view(string $name): string
    {
        return self::$namedRoutes[$name] ?? '#';
    }

    public static function assets(string $path, bool $isEcho = true)
    {
        $public_folder = '/public/assets';
        $fullPath = $public_folder . '/' . $path;
        if ($isEcho) {
            echo $fullPath;
        } else {
            return $fullPath;
        }
    }

    public static function notFound(callable $callback)
    {
        self::$notFound = $callback;
    }

    public static function redirect(string $path): void
    {
        header('Location:' . $path);
    }

    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        $routes = self::$routes[$method] ?? [];

        foreach ($routes as $route) {
            $pattern = preg_replace('#\{([^}]+)\}#', '([^/]+)', $route['uri']);
            $pattern = "#^" . rtrim($pattern, '/') . "/?$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // remove full match
                $params = [];

                // Extract named parameters from URI
                if (preg_match_all('#\{([^}]+)\}#', $route['uri'], $paramNames)) {
                    foreach ($paramNames[1] as $index => $name) {
                        $params[$name] = $matches[$index] ?? null;
                    }
                }

                $routeKey = "$method:{$route['uri']}";
                $middlewareList = self::$routeMiddleware[$routeKey] ?? [];
                $role = Session::get('user_role');
                $role = strtolower($role ?? '');

                foreach ($middlewareList as $mw) {

                    if ($mw === 'superadmin') {
                        if (empty($role) || $role !== 'superadmin') {
                            View::render(AUTH);
                            return;
                        }
                    }

                    if ($mw === 'admin') {
                        if (empty($role) || $role !== 'admin') {
                            View::render(AUTH);
                            return;
                        }
                    }

                    if ($mw === 'user') {
                        if (empty($role)) {
                            View::render(AUTH);
                            return;
                        }
                    }
                }

                $handler = $route['handler'];

                // Support: string "Controller@method"
                if (is_string($handler) && str_contains($handler, '@')) {
                    [$class, $method] = explode('@', $handler);
                    $class = 'App\\Controllers\\' . $class;

                    if (class_exists($class)) {
                        $instance = new $class;
                        if (method_exists($instance, $method)) {
                            call_user_func_array([$instance, $method], $params);
                            return;
                        } else {
                            throw new Exception("Method $method not found in $class");
                        }
                    } else {
                        throw new Exception("Class $class not found");
                    }
                }

                if (is_callable($handler)) {
                    $result = call_user_func_array($handler, $params);
                    if ($result !== null) {
                        echo $result;
                    }
                } elseif (is_array($handler) && count($handler) === 2) {
                    [$class, $method] = $handler;
                    if (class_exists($class)) {
                        $instance = new $class;
                        if (method_exists($instance, $method)) {
                            call_user_func_array([$instance, $method], $params);
                        } else {
                            throw new Exception("Method $method not found in $class");
                        }
                    } else {
                        throw new Exception("Class $class not found");
                    }
                } elseif (is_string($handler)) {
                    View::render($handler, $route['data'] ?? []);
                }

                return;
            }
        }

        if (self::$notFound) {
            call_user_func(self::$notFound);
        } else {
            View::render('pageNotFound');
        }
    }
}