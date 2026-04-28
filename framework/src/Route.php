<?php

namespace R2Packages\Framework;

use Exception;


class Route
{
    private $routes = [
        'get'    => [],
        'post'   => [],
        'delete' => [],
    ];

    private $currentPath              = '';
    private $currentMethod            = '';
    private $defaultGlobalMiddleware  = [];
    private $defaultGlobalPrefix      = [];
    private $nameHash                 = [];

    public function prefix($prefix, callable $callback)
    {
        $this->defaultGlobalPrefix[] = $prefix;
        $callback($this);
        array_pop($this->defaultGlobalPrefix);
        return $this;
    }

    public function name($name)
    {
        $this->routes[$this->currentMethod][$this->currentPath]['name'] = $name;
        $this->nameHash[$name] = $this->currentPath;
        return $this;
    }

    public function get($path, $callback)
    {
        $path = $this->getResolvedPath($path);

        $this->routes['get'][$path] = [
            'callback'   => $callback,
            'middleware' => $this->defaultGlobalMiddleware,
            'inject'     => [],
        ];
        $this->currentPath   = $path;
        $this->currentMethod = 'get';
        return $this;
    }

    public function post($path, $callback)
    {
        $path = $this->getResolvedPath($path);

        $this->routes['post'][$path] = [
            'callback'   => $callback,
            'middleware' => $this->defaultGlobalMiddleware,
            'inject'     => [],
        ];
        $this->currentPath   = $path;
        $this->currentMethod = 'post';
        return $this;
    }

    public function delete($path, $callback)
    {
        $path = $this->getResolvedPath($path);

        $this->routes['delete'][$path] = [
            'callback'   => $callback,
            'middleware' => $this->defaultGlobalMiddleware,
            'inject'     => [],
        ];
        $this->currentPath   = $path;
        $this->currentMethod = 'delete';
        return $this;
    }

    public function globalMiddleware($middleware, callable $callback)
    {
        $oldMiddleware                    = $this->defaultGlobalMiddleware;
        $this->defaultGlobalMiddleware    = array_merge($oldMiddleware, $middleware);
        $callback($this);
        $this->defaultGlobalMiddleware    = $oldMiddleware;
        return $this;
    }

    public function returnUrl($path)
    {
        $this->routes[$this->currentMethod][$this->currentPath]['returnUrl'] = $path;
        return $this;
    }

    public function actionUrl($path)
    {
        $this->routes[$this->currentMethod][$this->currentPath]['actionUrl'] = $path;
        return $this;
    }

    /*
    public function middleware($middleware)
    {
        $this->routes[$this->currentMethod][$this->currentPath]['middleware'] = $middleware;
        return $this;
    }

    public function inject($inject)
    {
        $this->routes[$this->currentMethod][$this->currentPath]['inject'] = $inject;
        return $this;
    }
    */

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

    public function getRouteByName($name, $params = [])
    {
        $path = $this->nameHash[$name];
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', $value, $path);
        }
        return $path;
    }

    // Placeholder for camelCaseToSnakeCase function
    public function camelCaseToSnakeCase($string)
    {
        return $string;
    }

    public function run($path, $method)
    {
        $method = strtolower($method);

        $path = trim($path, '/');
        if (empty($path)) {
            $path = '/';
        }
        $path = explode('?', $path)[0];

        $dispatcher = new Dispatcher();
        $request    = array_merge($_REQUEST, $_FILES);
        $found      = false;

        if (!isset($this->routes[$method])) {
            Utils::dd("Request method not supported");
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
                        $instance = new $middleware();
                        $instance->handle($request);
                    }
    
                    $args = [$request];

                    if (is_callable($callback['callback'])) {
                        $callback['callback']($request);
                    } elseif (is_array($callback['callback'])) {
                        $cls       = $callback['callback'][0];
                        $clsMethod = $callback['callback'][1];
                        $clsObj    = new $cls($request);
    
                        if (method_exists($clsObj, $clsMethod)) {
                            call_user_func_array([$clsObj, $clsMethod], $args);
                            exit;
                        } else {
                            Utils::dd("Method " . $clsMethod . " does not exist in class " . $cls);
                        }
                    }
    
                } catch (Exception $e) {
                    Utils::jsonResponse(['message' => $e->getMessage(),'success' => false], 500);
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
