<?php

namespace ViragRouter;

class Route
{
    private $method;
    private $pattern;
    private $handler;
    private $name;
    private $middleware = [];
    private $allowedMethods = [];
    private $routeParams = [];
    private $constraints = [];
    protected static $router;

    public function __construct(string $method, string $pattern, array $handler, string $name = null)
    {
        $this->method = strtoupper($method);
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->name = $name;
    }

    public function addMiddleware(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function allowMethods(array $methods): void
    {
        $this->allowedMethods = array_map('strtoupper', $methods);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function isMethodAllowed(string $method): bool
    {
        return empty($this->allowedMethods) || in_array(strtoupper($method), $this->allowedMethods);
    }

    public function setConstraint(string $key, callable $constraint): void
    {
        $this->constraints[$key] = $constraint;
    }

    public function matchesConstraints(array $params): bool
    {
        foreach ($params as $key => $value) {
            if (isset($this->constraints[$key])) {
                $constraint = $this->constraints[$key];
                if (!$constraint($value)) {
                    return false;
                }
            }
        }
        return true;
    }

    // Route Static Facade 
    public static function __callStatic($method, $args)
    {
        if (!static::$router) {
            static::$router = new \ViragRouter\Router();
        }

        if (method_exists(static::$router, $method)) {
            return call_user_func_array([static::$router, $method], $args);
        }

        throw new \BadMethodCallException("Method {$method} does not exist");
    }

    public static function middleware(string $name, callable $middleware): void
    {
        if (!static::$router) {
            static::$router = new \ViragRouter\Router();
        }

        static::$router->addMiddleware($name, $middleware);
    }

    public static function group(array $attributes, callable $callback): void
    {
        if (!static::$router) {
            static::$router = new \ViragRouter\Router();
        }

        static::$router->group($attributes, $callback);
    }

    public static function constraint(string $key, callable $constraint): void
    {
        if (!static::$router) {
            static::$router = new \ViragRouter\Router();
        }

        static::$router->addConstraint($key, $constraint);
    }

    public static function cacheRoutes(): void
    {
        if (!static::$router) {
            static::$router = new \ViragRouter\Router();
        }

        static::$router->cacheRoutes();
    }

    public static function generateUrl(string $name, array $params = []): ?string
    {
        if (!static::$router) {
            static::$router = new \ViragRouter\Router();
        }

        return static::$router->generateUrl($name, $params);
    }

    public static function getRouteByName(string $name): ?Route
    {
        if (!static::$router) {
            static::$router = new \ViragRouter\Router();
        }

        return static::$router->getRouteByName($name);
    }
}
