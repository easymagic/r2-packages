<?php

namespace R2Packages\Framework\Ecommerce;

use R2Packages\Framework\Ecommerce\Controllers\ActiveProductController;
use R2Packages\Framework\Ecommerce\Controllers\ActiveProductImageController;
use R2Packages\Framework\Ecommerce\Controllers\CategoryController;
use R2Packages\Framework\Ecommerce\Controllers\EcommerceMigrationController;
use R2Packages\Framework\Ecommerce\Controllers\ProductController;
use R2Packages\Framework\Ecommerce\Controllers\ProductImageController;
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
        $this->route->prefix('v1/ecommerce', function (Route $route) {


            $route->globalMiddleware([
                GlobalApiMiddleware::class,
                AdminMiddleware::class
            ], function (Route $route) {



                $route->globalMiddleware([
                    AdminMiddleware::class
                ], function (Route $route) {

                    // admin only routes
                    $route->prefix("admin", function (Route $route) {

                        // ecommerce migration
                        $route->post('/migrate', [EcommerceMigrationController::class, 'migrate']);

                        // categories
                        $route->get('/categories', [CategoryController::class, 'index']);
                        $route->post('/categories', [CategoryController::class, 'create']);
                        $route->post('/categories/{category_id}', [CategoryController::class, 'update']);
                        $route->delete('/categories/{category_id}', [CategoryController::class, 'delete']);
                        $route->get('/categories/{category_id}', [CategoryController::class, 'get']);


                        // products
                        $route->get('/products', [ProductController::class, 'index']);
                        $route->post('/products/{user_id}', [ProductController::class, 'create']);
                        $route->post('/products/{user_id}/{product_id}', [ProductController::class, 'update']);
                        $route->delete('/products/{user_id}/{product_id}', [ProductController::class, 'delete']);
                        $route->get('/products/{product_id}', [ProductController::class, 'get']);


                        // product images
                        $route->get('/products/{product_id}/images', [ProductImageController::class, 'index']);
                        $route->post('/products/{product_id}/images', [ProductImageController::class, 'create']);
                        $route->post('/products/{product_id}/images/{product_image_id}', [ProductImageController::class, 'update']);
                        $route->delete('/products/{product_id}/images/{product_image_id}', [ProductImageController::class, 'delete']);
                        $route->get('/products/{product_id}/images/{product_image_id}', [ProductImageController::class, 'get']);
                    });
                });

                // public routes
                $route->get('/categories', [CategoryController::class, 'getActiveCategories']);

                // active products
                $route->get('/products', [ActiveProductController::class, 'index']);
                $route->get('/products/{product_id}', [ActiveProductController::class, 'get']);

                // active product images
                $route->get('/products/{product_id}/images', [ActiveProductImageController::class, 'index']);
                $route->get('/products/{product_id}/images/{product_image_id}', [ActiveProductImageController::class, 'get']);
            });
        });
    }
}
