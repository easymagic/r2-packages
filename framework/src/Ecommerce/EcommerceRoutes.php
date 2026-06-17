<?php

namespace R2Packages\Framework\Ecommerce;

use R2Packages\Framework\Ecommerce\Controllers\ActiveProductController;
use R2Packages\Framework\Ecommerce\Controllers\CategoryController;
use R2Packages\Framework\Ecommerce\Controllers\ProductController;
use R2Packages\Framework\middlewares\AdminMiddleware;
use R2Packages\Framework\middlewares\GlobalApiMiddleware;
use R2Packages\Framework\Route;

class EcommerceRoutes
{

    private Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function registerRoutes()
    {
        $this->route->prefix('api/v1/ecommerce', function (Route $route) {


            $route->globalMiddleware([
                GlobalApiMiddleware::class,
                AdminMiddleware::class
            ], function (Route $route) {


                
                $route->globalMiddleware([
                    AdminMiddleware::class
                ], function (Route $route) {
                    // admin only routes

                    // categories
                    $route->get('/admin/categories', [CategoryController::class, 'index']);
                    $route->post('/admin/categories', [CategoryController::class, 'create']);
                    $route->post('/admin/categories/{category_id}', [CategoryController::class, 'update']);
                    $route->delete('/admin/categories/{category_id}', [CategoryController::class, 'delete']);
                    $route->get('/admin/categories/{category_id}', [CategoryController::class, 'get']);


                    // products
                    $route->get('/admin/products', [ProductController::class, 'index']);
                    $route->post('/admin/products', [ProductController::class, 'create']);
                    $route->post('/admin/products/{product_id}', [ProductController::class, 'update']);
                    $route->delete('/admin/products/{product_id}', [ProductController::class, 'delete']);
                    $route->get('/admin/products/{product_id}', [ProductController::class, 'get']);
                });

                // public routes
                $route->get('/categories', [CategoryController::class, 'getActiveCategories']);

                // active products
                $route->get('/products', [ActiveProductController::class, 'index']);
                $route->get('/products/{product_id}', [ActiveProductController::class, 'get']);


            });


        });
    }
}