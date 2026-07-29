<?php

namespace R2Packages\Framework\Ecommerce\Product;

use R2Packages\Framework\Request;
use R2Packages\Framework\BaseUser\UserIdService;

class ProductController
{
    private ProductService $productService;
    private Request $request;

    public function __construct(
        ProductService $productService,
        Request $request,
    ) {
        $this->productService = $productService;
        $this->request = $request;
    }

    public function index()
    {
        $products = $this->productService->fetch();
        jsonResponse([
            'message' => 'Products fetched successfully',
            'data' => $products,
            "success" => true
        ]);
    }

    public function create()
    {
        $product = $this->productService->create($this->request);
        jsonResponse([
            'message' => 'Product created successfully',
            'data' => $product,
            "success" => true
        ]);
    }

    public function update()
    {
        $product = $this->productService->update($this->request);
        jsonResponse([
            'message' => 'Product updated successfully',
            'data' => $product,
            "success" => true
        ]);
    }

    public function get()
    {
        $product = $this->productService->one();
        jsonResponse([
            'message' => 'Product fetched successfully',
            'data' => $product,
            "success" => true
        ]);
    }

    public function delete()
    {
        $this->productService->delete();
        jsonResponse([
            'message' => 'Product deleted successfully',
            "success" => true
        ]);
    }
}
