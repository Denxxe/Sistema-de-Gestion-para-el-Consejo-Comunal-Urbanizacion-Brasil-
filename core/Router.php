<?php
namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middleware = [];
    private string $namespace = 'App\\controllers\\'; // Ajustado según tu estructura

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

    public function put(string $pattern, $handler, array $middleware = []): void
    {
        $this->add('PUT', $pattern, $handler, $middleware);
    }

    public function delete(string $pattern, $handler, array $middleware = []): void
    {
        $this->add('DELETE', $pattern, $handler, $middleware);
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
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Eliminar el directorio base del proyecto de la URI
        $baseDir = '/Sistema-de-Gestion-para-el-Consejo-Comunal-Urbanizacion-Brasil-/public';
        if (strpos($uri, $baseDir) === 0) {
            $uri = substr($uri, strlen($baseDir));
        }
        
        $uri = $uri ?: '/';
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
            
            if (is_string($controller)) {
                $controllerClass = $this->namespace . $controller;
                if (!class_exists($controllerClass)) {
                    throw new \RuntimeException("Controller {$controllerClass} not found");
                }
                $controller = new $controllerClass();
            }

            if (!method_exists($controller, $method)) {
                throw new \RuntimeException("Method {$method} not found in controller class");
            }

            $inputData = file_get_contents("php://input");
            $body = json_decode($inputData, true) ?? $_POST;

            $result = call_user_func_array([$controller, $method], array_merge([$body], $params));
            if ($result !== null) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        } elseif (is_callable($route['handler'])) {
            $result = call_user_func_array($route['handler'], $params);
            if ($result !== null) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }
    }
}
