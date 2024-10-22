<?php
declare(strict_types=1);

require_once __DIR__ . '/RateLimiter.php';
require_once __DIR__ . '/Security.php';

class Router {
    private array $routes = [];

    public function addRoute(string $method, string $path, string $handler, bool $requireAuth = false): void {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'requireAuth' => $requireAuth
        ];
    }

    public function handleRequest(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $ip = $_SERVER['REMOTE_ADDR'];

        if (!RateLimiter::checkRateLimit($ip)) {
            http_response_code(429);
            echo json_encode(['error' => 'Rate limit exceeded']);
            return;
        }

        $remainingRequests = RateLimiter::getRemainingRequests($ip);
        header('X-RateLimit-Remaining: ' . $remainingRequests);

        foreach ($this->routes as $route) {
            $pattern = $this->convertPathToRegex($route['path']);
            if ($route['method'] === $method && preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove the full match

                if ($route['requireAuth']) {
                    $token = $this->getBearerToken();
                    $payload = Security::verifyJWT($token);
                    if (!$payload) {
                        http_response_code(401);
                        echo json_encode(['error' => 'Unauthorized']);
                        return;
                    }
                }

                $this->callHandler($route['handler'], $matches);
                return;
            }
        }

        // If no route matches, return a 404 response
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }

    private function convertPathToRegex(string $path): string {
        return '#^' . preg_replace('#/:([^/]+)#', '/(?<$1>[^/]+)', $path) . '$#';
    }

    private function callHandler(string $handler, array $params): void {
        [$controllerName, $methodName] = explode('@', $handler);
        $controllerFile = __DIR__ . "/controllers/{$controllerName}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();
            $controller->$methodName(...$params);
        } else {
            throw new Exception("Controller file not found: {$controllerFile}");
        }
    }

    private function getBearerToken(): ?string {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}