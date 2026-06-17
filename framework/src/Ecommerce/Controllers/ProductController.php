<?php

namespace R2Packages\Framework\Ecommerce\Controllers;

use R2Packages\Framework\Ecommerce\Repositories\Filters\MyProductRepository;
use R2Packages\Framework\Ecommerce\Repositories\ProductRepository;
use R2Packages\Framework\Ecommerce\Services\Ids\ProductIdService;
use R2Packages\Framework\Ecommerce\Services\ProductService;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;

class ProductController
{
    private ProductService $productService;
    private Request $request;
    private MyProductRepository $myProductRepository;
    private ProductIdService $productIdService;
    private AuthUserService $authUserService;

    public function __construct(
        ProductService $productService,
        Request $request,
        MyProductRepository $myProductRepository,
        ProductIdService $productIdService,
        AuthUserService $authUserService
    ) {
        $this->productService = $productService;
        $this->request = $request;
        $this->myProductRepository = $myProductRepository;
        $this->productIdService = $productIdService;
        $this->authUserService = $authUserService;
    }

    public function index()
    {
        $products = $this->myProductRepository->fetchAll();
        jsonResponse([
            'message' => 'Products fetched successfully',
            'data' => $products,
            "success" => true
        ]);
    }

    public function create()
    {
        $product = $this->productService->create($this->request, $this->authUserService->getAuthUser());
        jsonResponse([
            'message' => 'Product created successfully',
            'data' => $product,
            "success" => true
        ]);
    }

    public function update()
    {
        $product = $this->productService->update(
            $this->request,
            $this->productIdService->getProduct(),
            $this->authUserService->getAuthUser()
        );
        jsonResponse([
            'message' => 'Product updated successfully',
            'data' => $product,
            "success" => true
        ]);
    }

    // get product by id
    public function get()
    {
        $product = $this->productIdService->getProduct();
        jsonResponse([
            'message' => 'Product fetched successfully',
            'data' => $product,
            "success" => true
        ]);
    }

    // delete product by id
    public function delete()
    {
        $product = $this->productIdService->getProduct();
        $this->productService->delete(
            $product,
            $this->authUserService->getAuthUser()
        );
        jsonResponse([
            'message' => 'Product deleted successfully',
            "success" => true
        ]);
    }
}
