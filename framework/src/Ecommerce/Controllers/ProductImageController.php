<?php

namespace R2Packages\Framework\Ecommerce\Controllers;

use R2Packages\Framework\Ecommerce\Repositories\ProductImageRepository;
use R2Packages\Framework\Ecommerce\Services\Ids\ProductIdService;
use R2Packages\Framework\Ecommerce\Services\Ids\ProductImageIdService;
use R2Packages\Framework\Ecommerce\Services\ProductImageService;
use R2Packages\Framework\Request;

class ProductImageController
{
    private ProductImageService $productImageService;
    private Request $request;
    private ProductIdService $productIdService;
    private ProductImageIdService $productImageIdService;
    private ProductImageRepository $productImageRepository;

    public function __construct(
        ProductImageService $productImageService,
        Request $request,
        ProductIdService $productIdService,
        ProductImageIdService $productImageIdService,
        ProductImageRepository $productImageRepository
    ) {
        $this->productImageService = $productImageService;
        $this->request = $request;
        $this->productIdService = $productIdService;
        $this->productImageIdService = $productImageIdService;
        $this->productImageRepository = $productImageRepository;
    }

    public function index()
    {
        $productImages = $this->productImageRepository->fetchAll();
        jsonResponse([
            'message' => 'Product images fetched successfully',
            'data' => $productImages,
            "success" => true
        ]);
    }

    public function create()
    {
        $productImage = $this->productImageService->create($this->request, $this->productIdService->getProduct());
        jsonResponse([
            'message' => 'Product image created successfully',
            'data' => $productImage,
            "success" => true
        ]);
    }

    public function update()
    {
        $productImage = $this->productImageService->update($this->request, $this->productImageIdService->getProductImage());
        jsonResponse([
            'message' => 'Product image updated successfully',
            'data' => $productImage,
            "success" => true
        ]);
    }

    public function delete()
    {
        $this->productImageService->delete($this->productImageIdService->getProductImage());
        jsonResponse([
            'message' => 'Product image deleted successfully',
            "success" => true
        ]);
    }

    public function get()
    {
        $productImage = $this->productImageIdService->getProductImage();
        jsonResponse([
            'message' => 'Product image fetched successfully',
            'data' => $productImage,
            "success" => true
        ]);
    }
}
