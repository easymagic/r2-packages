# R2 Packages Framework

A small reusable PHP routing framework package.

## Requirements

- PHP 7.4 or higher
- Composer

## Installation

Install the package with Composer:

```bash
composer require r2-packages/framework
```

If you are using this package locally, require Composer's autoloader:

```php
require __DIR__ . '/vendor/autoload.php';
```

## Basic Usage

Create a router, register routes, and run the router with the current request path and method.

```php
use R2Packages\Framework\Route;

$route = new Route();

$route->get('/', function ($request) {
    echo 'Welcome';
});

$route->get('/users/{id}', function ($request) {
    echo 'User ID: ' . $request['id'];
});

$route->post('/users', [UserController::class, 'store']);
$route->delete('/users/{id}', [UserController::class, 'delete']);

$route->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
```

## Controller Routes

Controller routes should be registered as an array containing the class name and method name.

```php
$route->get('/users/{id}', [UserController::class, 'show']);
```

The controller is instantiated with the request array, and the matched method receives the request array.

```php
class UserController
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function show($request)
    {
        echo 'User ID: ' . $request['id'];
    }
}
```

## Route Prefixes

Use `prefix()` to group routes under a shared path.

```php
$route->prefix('api', function ($route) {
    $route->get('/users', [UserController::class, 'index']);
    $route->get('/users/{id}', [UserController::class, 'show']);
});
```

The example above registers:

- `api/users`
- `api/users/{id}`

## Middleware

Use `globalMiddleware()` to apply middleware classes to a group of routes.

```php
$route->globalMiddleware([AuthMiddleware::class], function ($route) {
    $route->get('/dashboard', [DashboardController::class, 'index']);
});
```

Middleware classes must provide a `handle()` method.

```php
class AuthMiddleware
{
    public function handle($request)
    {
        if (empty($request['Authorization'])) {
            R2Packages\Framework\Utils::jsonResponse([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
    }
}
```

## Named Routes

Use `name()` to assign a name to the most recently registered route, then generate the route path with `getRouteByName()`.

```php
$route->get('/users/{id}', [UserController::class, 'show'])->name('users.show');

$path = $route->getRouteByName('users.show', [
    'id' => 10,
]);

echo $path; // users/10
```

## Helpers

The `Utils` class provides helper methods for common responses and formatting.

```php
use R2Packages\Framework\Utils;

Utils::jsonResponse([
    'success' => true,
    'message' => 'Created successfully',
]);

Utils::dd($data);

$snakeCase = Utils::camelCaseToSnakeCase('createdAt');
```

## License

MIT
