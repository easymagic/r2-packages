<?php

namespace R2Packages\Framework\Ecommerce;

use R2Packages\Framework\Ecommerce\Controllers\CategoryController;
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
                });

                // public routes
                $route->get('/categories', [CategoryController::class, 'getActiveCategories']);


            });


        });
    }
}