<?php 

namespace R2Packages\Framework\Ecommerce\Product;

use R2Packages\Framework\Request;

class ActiveProductController
{
    private ActiveProductRepository $activeProductRepository;
    private Request $request;
    private ProductIdService $productIdService;

    public function __construct(
        ActiveProductRepository $activeProductRepository,
        Request $request,
        ProductIdService $productIdService,
    ) {
        $this->activeProductRepository = $activeProductRepository;
        $this->request = $request;
        $this->productIdService = $productIdService;
    }

    public function index()
    {
        $products = $this->activeProductRepository->fetchAll();
        jsonResponse([
            'message' => 'Products fetched successfully',
            'data' => $products,
            "success" => true
        ]);
    }

    public function get()
    {
        $product = $this->productIdService->getProduct();
        jsonResponse([
            'message' => 'Product fetched successfully',
            'data' => $product,
            "success" => true
        ]);
    }
}
