<?php
class Router {
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function post(string $path, string $controller, string $method): void {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch(): void {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $this->getUri();

        foreach ($this->routes[$httpMethod] ?? [] as $routePath => $handler) {
            $pattern = $this->buildPattern($routePath);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $controllerName = $handler['controller'];
                $methodName     = $handler['method'];

                $controllerFile = BASE_PATH . '/app/controllers/' . $controllerName . '.php';
                if (!file_exists($controllerFile)) {
                    $this->abort(500, "Controlador no encontrado: $controllerName");
                    return;
                }
                require_once $controllerFile;
                $ctrl = new $controllerName();
                call_user_func_array([$ctrl, $methodName], $matches);
                return;
            }
        }

        $this->abort(404, 'Página no encontrada');
    }

    private function getUri(): string {
        $uri = $_GET['url'] ?? '/';
        $uri = '/' . trim($uri, '/');
        return strtok($uri, '?');
    }

    private function buildPattern(string $path): string {
        $pattern = preg_replace('/\{[a-z_]+\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function abort(int $code, string $message): void {
        http_response_code($code);
        echo "<h1>$code</h1><p>$message</p>";
    }
}
