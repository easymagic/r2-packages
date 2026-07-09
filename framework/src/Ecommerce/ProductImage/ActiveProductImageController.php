<?php 

namespace R2Packages\Framework\Ecommerce\ProductImage;

class ActiveProductImageController
{
    private ActiveProductImageRepository $activeProductImageRepository;

    public function __construct(
        ActiveProductImageRepository $activeProductImageRepository
    ) {
        $this->activeProductImageRepository = $activeProductImageRepository;
    }

    public function index()
    {
        $productImages = $this->activeProductImageRepository->fetchAll();
        jsonResponse([
            'message' => 'Product images fetched successfully',
            'data' => $productImages,
            "success" => true
        ]);
    }
}
