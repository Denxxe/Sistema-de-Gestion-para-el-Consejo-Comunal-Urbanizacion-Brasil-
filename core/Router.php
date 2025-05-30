<?php
namespace Core;

class Router
{
    private array $routes = [];
    private array $middleware = [];
    private string $namespace = 'Controllers\\'; // Ajustado según tu estructura

    public function add(string $method, string $pattern, $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function get(string $pattern, $handler, array $middleware = []): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
    }

    public function post(string $pattern, $handler, array $middleware = []): void
    {
        $this->add('POST', $pattern, $handler, $middleware);
    }

    public function addMiddleware(string $name, callable $middleware): void
    {
        $this->middleware[$name] = $middleware;
    }

    private function matchRoute(string $method, string $uri): ?array
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace('/\{([^}]+)\}/', '(?P<\1>[^/]+)', $route['pattern']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                return [
                    'route' => $route,
                    'params' => array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)
                ];
            }
        }
        return null;
    }

    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = isset($_GET['url']) ? '/' . rtrim($_GET['url'], '/') : '/';
        $uri = filter_var($uri, FILTER_SANITIZE_URL);

        $match = $this->matchRoute($method, $uri);

        if (!$match) {
            http_response_code(404);
            echo json_encode(["statusCode" => 404, "message" => "Ruta no encontrada"]);
            return;
        }

        $route = $match['route'];
        $params = $match['params'];

        foreach ($route['middleware'] as $middlewareName) {
            if (isset($this->middleware[$middlewareName])) {
                $response = call_user_func($this->middleware[$middlewareName]);
                if ($response === false) {
                    return;
                }
            }
        }

        if (is_array($route['handler'])) {
            [$controller, $method] = $route['handler'];
            $controllerClass = $this->namespace . $controller;

            if (!class_exists($controllerClass)) {
                throw new \RuntimeException("Controller {$controllerClass} not found");
            }

            $controller = new $controllerClass();
            if (!method_exists($controller, $method)) {
                throw new \RuntimeException("Method {$method} not found in controller {$controllerClass}");
            }

            $inputData = file_get_contents("php://input");
            $body = json_decode($inputData, true) ?? $_POST;

            call_user_func_array([$controller, $method], array_merge([$body], $params));
        } elseif (is_callable($route['handler'])) {
            call_user_func_array($route['handler'], $params);
        }
    }
}
