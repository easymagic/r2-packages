<?php

namespace R2Packages\Framework\Infrastructure;

use Exception;
use R2Packages\Framework\Application\ContainerServiceInterface;
use R2Packages\Framework\Application\DispatcherServiceInterface;
use R2Packages\Framework\Application\RouteServiceInterface;
use R2Packages\Framework\Utils;

class RouteService implements RouteServiceInterface
{

    private DispatcherServiceInterface $dispatcherService;
    private ContainerServiceInterface $containerService;

    public function __construct(DispatcherServiceInterface $dispatcherService, ContainerServiceInterface $containerService)
    {
        $this->dispatcherService = $dispatcherService;
        $this->containerService = $containerService;
    }

    private $routes = [
        'get'    => [],
        'post'   => [],
        'delete' => [],
        'put'    => []
    ];

    private $currentPath              = '';
    private $currentMethod            = '';
    private $defaultGlobalMiddleware  = [];
    private $defaultGlobalPrefix      = [];
    private $nameHash                 = [];


    private function getResolvedPath($path)
    {
        $path = trim($path, '/');
        if (!empty($this->defaultGlobalPrefix)) {
            $last = implode('/', $this->defaultGlobalPrefix);
            $path = empty($path) ? $last : ($last . '/' . $path);
        }
        if (empty($path)) {
            $path = '/';
        }
        return $path;
    }


    public function get(string $path, callable $callback)
    {
        $path = $this->getResolvedPath($path);

        $this->routes['get'][$path] = [
            'callback'   => $callback,
            'middleware' => $this->defaultGlobalMiddleware
        ];
        $this->currentPath   = $path;
        $this->currentMethod = 'get';
        return $this;
    }

    public function post(string $path, callable $callback)
    {
        $path = $this->getResolvedPath($path);

        $this->routes['post'][$path] = [
            'callback'   => $callback,
            'middleware' => $this->defaultGlobalMiddleware
        ];
        $this->currentPath   = $path;
        $this->currentMethod = 'post';
        return $this;
    }

    public function delete(string $path, callable $callback)
    {
        $path = $this->getResolvedPath($path);

        $this->routes['delete'][$path] = [
            'callback'   => $callback,
            'middleware' => $this->defaultGlobalMiddleware
        ];
        $this->currentPath   = $path;
        $this->currentMethod = 'delete';
        return $this;
    }

    public function put(string $path, callable $callback)
    {
        $path = $this->getResolvedPath($path);

        $this->routes['put'][$path] = [
            'callback'   => $callback,
            'middleware' => $this->defaultGlobalMiddleware
        ];
        $this->currentPath   = $path;
        $this->currentMethod = 'put';
        return $this;
    }

    public function prefix(string $prefix, callable $callback)
    {
        $this->defaultGlobalPrefix[] = $prefix;
        $callback($this);
        array_pop($this->defaultGlobalPrefix);
        return $this;
    }

    public function middleware(array $middleware, callable $callback)
    {
        $oldMiddleware                    = $this->defaultGlobalMiddleware;
        $this->defaultGlobalMiddleware    = array_merge($oldMiddleware, $middleware);
        $callback($this);
        $this->defaultGlobalMiddleware    = $oldMiddleware;
        return $this;
    }

    public function run(string $path, string $method)
    {
        $method = strtolower($method);

        $path = trim($path, '/');
        if (empty($path)) {
            $path = '/';
        }
        $path = explode('?', $path)[0];

        $dispatcher = $this->dispatcherService;
        $request    = array_merge($_REQUEST, $_FILES);
        $found      = false;

        if (!isset($this->routes[$method])) {
            Utils::dd("Request method not supported..");
        }

        foreach ($this->routes[$method] as $route => $callback) {
            if ($dispatcher->match($path, $route)) {
                $found  = true;
                $params = $dispatcher->getParams();

                $request = array_merge($request, $params, $_REQUEST, $_FILES);
                $headers = getallheaders();
                $request = array_merge($request, $headers);
                $_REQUEST = $request;

                try {

                    foreach ($callback['middleware'] as $middleware) {
                        $instance = $this->containerService->get($middleware, $request);
                        $instance->handle();
                    }

                    $args = [$request];

                    if (is_callable($callback['callback'])) {
                        $callback['callback']($request);
                    } elseif (is_array($callback['callback'])) {
                        $cls       = $callback['callback'][0];
                        $clsMethod = $callback['callback'][1];
                        $clsObj    = $this->containerService->get($cls, $request);

                        if (method_exists($clsObj, $clsMethod)) {
                            call_user_func_array([$clsObj, $clsMethod], $args);
                            exit;
                        } else {
                            Utils::dd("Method " . $clsMethod . " does not exist in class " . $cls);
                        }
                    }
                } catch (Exception $e) {
                    Utils::jsonResponse(['message' => $e->getMessage(), 'success' => false], 500);
                }

                return $callback;
            }
        }

        if (!$found) {
            // dd("Route not found", $path, $method, $this->routes[$method]);

            Utils::dd("Route not found");
        }
    }
}
