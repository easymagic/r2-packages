<?php 

namespace R2Packages\Framework\Ecommerce\ProductImage\Filters;

class ActiveProductImageController
{
    private ActiveProductImageService $activeProductImageService;

    public function __construct(
        ActiveProductImageService $activeProductImageService
    ) {
        $this->activeProductImageService = $activeProductImageService;
    }

    public function index()
    {
        $productImages = $this->activeProductImageService->fetchAll();
        jsonResponse([
            'message' => 'Product images fetched successfully',
            'data' => $productImages,
            "success" => true
        ]);
    }
}
