<?php
// app/core/Router.php

class Router
{
    private array $routes = [];

    public function get(string $path, string $controllerAction): void
    {
        $this->routes['GET'][$path] = $controllerAction;
    }

    public function post(string $path, string $controllerAction): void
    {
        $this->routes['POST'][$path] = $controllerAction;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // Strip base URL and query string from the requested path
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = '';
        $path = str_replace($basePath, '', $uri);
        $path = $path === '' ? '/' : $path;

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "404 - Page not found";
            return;
        }

        [$controllerName, $actionName] = explode('@', $this->routes[$method][$path]);
        $controllerFile = BASE_PATH . "/app/controllers/{$controllerName}.php";

        if (!file_exists($controllerFile)) {
            http_response_code(500);
            echo "Controller not found: {$controllerName}";
            return;
        }

        require_once $controllerFile;

        $controller = new $controllerName();
        $controller->$actionName();
    }
}