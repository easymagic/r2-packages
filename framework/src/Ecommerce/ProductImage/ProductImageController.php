<?php

namespace R2Packages\Framework\Ecommerce\ProductImage;

use R2Packages\Framework\Ecommerce\Product\ProductIdService;
use R2Packages\Framework\Request;

class ProductImageController
{
    private ProductImageService $productImageService;
    private Request $request;

    public function __construct(
        ProductImageService $productImageService,
        Request $request,
    ) {
        $this->productImageService = $productImageService;
        $this->request = $request;
    }

    public function index()
    {
        $productImages = $this->productImageService->fetchAll();
        jsonResponse([
            'message' => 'Product images fetched successfully',
            'data' => $productImages,
            "success" => true
        ]);
    }

    public function create()
    {
        $productImage = $this->productImageService->create($this->request);
        jsonResponse([
            'message' => 'Product image created successfully',
            'data' => $productImage,
            "success" => true
        ]);
    }

    public function update()
    {
        $productImage = $this->productImageService->update($this->request);
        jsonResponse([
            'message' => 'Product image updated successfully',
            'data' => $productImage,
            "success" => true
        ]);
    }

    public function delete()
    {
        $this->productImageService->delete();
        jsonResponse([
            'message' => 'Product image deleted successfully',
            "success" => true
        ]);
    }

    public function get()
    {
        $productImage = $this->productImageService->one();
        jsonResponse([
            'message' => 'Product image fetched successfully',
            'data' => $productImage,
            "success" => true
        ]);
    }
}
