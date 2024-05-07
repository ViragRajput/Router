<?php

namespace Virag\Router;

use Virag\HttpFoundation\Request;
use Virag\HttpFoundation\Response;


class Router
{
    private $routes = [];
    private $namedRoutes = [];
    private $routeParams = [];
    private $middlewareGroups = [];
    private $routeGroupAttributes = [];
    private $routePrefix = '';
    private $routeConstraints = [];
    private $cacheFile;

    public function addRoute(string $method, string $pattern, array $handler, string $name = null): Route
    {
        $pattern = rtrim($pattern, '/');
        $route = new Route($method, $pattern, $handler, $name);
        $this->routes[] = $route;

        if ($name !== null) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }

    public function addMiddleware(string $name, callable $middleware): void
    {
        $this->middlewareGroups[$name] = $middleware;
    }

    public function addMiddlewareGroup(string $name, array $middleware): void
    {
        $this->middlewareGroups[$name] = $middleware;
    }

    public function addConstraint(string $key, callable $constraint): void
    {
        $this->routeConstraints[$key] = $constraint;
    }

    public function addRouteGroupAttribute(string $key, $value): void
    {
        $this->routeGroupAttributes[$key] = $value;
    }

    public function cacheRoutes(): void
    {
        $cachedRoutes = var_export($this->routes, true);
        $cacheContent = "<?php\n\nreturn $cachedRoutes;\n";
        file_put_contents($this->cacheFile, $cacheContent);
    }

    public function generateUrl(string $name, array $params = []): ?string
    {
        $route = $this->getRouteByName($name);

        if ($route !== null) {
            $patternParts = explode('/', trim($route->getPattern(), '/'));
            $url = '';

            foreach ($patternParts as $part) {
                if ($part[0] === '{' && $part[strlen($part) - 1] === '}') {
                    $url .= '/' . ($params[$part] ?? '');
                } else {
                    $url .= '/' . $part;
                }
            }

            return $url;
        }

        return null;
    }

    public function getRouteByName(string $name): ?Route
    {
        return $this->namedRoutes[$name] ?? null;
    }

    public function group(array $attributes, callable $callback): void
    {
        $previousAttributes = $this->routeGroupAttributes;
        $this->routeGroupAttributes = array_merge($this->routeGroupAttributes, $attributes);
        $callback($this);
        $this->routeGroupAttributes = $previousAttributes;
    }

    private function applyGroupAttributes(Route $route): void
    {
        foreach ($this->routeGroupAttributes as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($route, $method)) {
                $route->$method($value);
            }
        }
    }

    private function executeHandler($handler): void
    {
        // Execute the route handler
        if (is_callable($handler)) {
            $handler();
        }
    }

    private function executeMiddleware(array $middleware): void
    {
        foreach ($middleware as $m) {
            if (is_callable($m)) {
                $m();
            }
        }
    }

    private function handleNotFound(): void
    {
        echo "404 Not Found";
    }

    private function matchesPattern(string $pattern, string $uri): bool
    {
        list($path, $query) = explode('?', $uri, 2) + ['', ''];

        $pattern = preg_replace_callback('/\{(\w+)(?::([^{}]+))?(\?)?\}/', function ($matches) {
            $paramName = $matches[1];
            $paramPattern = isset($matches[2]) ? $matches[2] : '[^\/]+';
            $optional = isset($matches[3]) ? '?' : '';
            return "(?P<$paramName>$paramPattern$optional)";
        }, $pattern);

        $pattern = str_replace('\*', '.*', preg_quote($pattern, '/'));

        $pattern = '/^' . $pattern . '$/i';

        $matches = [];
        $isMatch = preg_match($pattern, $path, $matches);

        if ($isMatch) {
            $this->routeParams = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            unset($this->routeParams[0]);
        }

        parse_str($query, $queryParams);
        $this->routeParams += $queryParams;

        return (bool)$isMatch;
    }

    // Http Method

    public function get(string $pattern, array $handler): Route
    {
        if (is_callable($handler)) {
            $handler = [$handler];
        } else if (!is_array($handler)) {
            throw new \InvalidArgumentException('Route handler must be a closure or an array');
        }

        $route = $this->addRoute('GET', $pattern, $handler);
        return $route;
    }

    public function post(string $pattern, array $handler): Route
    {
        if (is_callable($handler)) {
            $handler = [$handler];
        } else if (!is_array($handler)) {
            throw new \InvalidArgumentException('Route handler must be a closure or an array');
        }

        $route = $this->addRoute('POST', $pattern, $handler);
        return $route;
    }

    public function put(string $pattern, array $handler): Route
    {
        if (is_callable($handler)) {
            $handler = [$handler];
        } else if (!is_array($handler)) {
            throw new \InvalidArgumentException('Route handler must be a closure or an array');
        }
        $route = $this->addRoute('PUT', $pattern, $handler);
        return $route;
    }

    public function delete(string $pattern, array $handler): Route
    {
        if (is_callable($handler)) {
            $handler = [$handler];
        } else if (!is_array($handler)) {
            throw new \InvalidArgumentException('Route handler must be a closure or an array');
        }
        $route = $this->addRoute('DELETE', $pattern, $handler);
        return $route;
    }

    public function patch(string $pattern, array $handler): Route
    {
        if (is_callable($handler)) {
            $handler = [$handler];
        } else if (!is_array($handler)) {
            throw new \InvalidArgumentException('Route handler must be a closure or an array');
        }
        $route = $this->addRoute('PATCH', $pattern, $handler);
        return $route;
    }

    public function head(string $pattern, array $handler): Route
    {
        if (is_callable($handler)) {
            $handler = [$handler];
        } else if (!is_array($handler)) {
            throw new \InvalidArgumentException('Route handler must be a closure or an array');
        }
        $route = $this->addRoute('HEAD', $pattern, $handler);
        return $route;
    }

    public function options(string $pattern, array $handler): Route
    {
        if (is_callable($handler)) {
            $handler = [$handler];
        } else if (!is_array($handler)) {
            throw new \InvalidArgumentException('Route handler must be a closure or an array');
        }
        $route = $this->addRoute('OPTIONS', $pattern, $handler);
        return $route;
    }

    public function any(string $pattern, array $handler): Route
    {
        if (is_callable($handler)) {
            $handler = [$handler];
        } else if (!is_array($handler)) {
            throw new \InvalidArgumentException('Route handler must be a closure or an array');
        }
        $route = $this->addRoute('GET|POST|PUT|PATCH|DELETE|OPTIONS|HEAD', $pattern, $handler);
        return $route;
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $uri = $request->getPath();

        foreach ($this->routes as $route) {
            if ($route->getMethod() === $method && $this->matchesPattern($route->getPattern(), $uri)) {
                $this->executeMiddleware($route->getMiddleware());
                $this->executeHandler($route->getHandler());
                return new Response();
            }
        }

        $this->handleNotFound();
        // Return a default 404 response if no route matched
        return new Response('404 Not Found', 404);
    }
}
