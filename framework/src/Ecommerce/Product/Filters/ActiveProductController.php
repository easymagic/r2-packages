<?php 

namespace R2Packages\Framework\Ecommerce\Product\Filters;

use R2Packages\Framework\Ecommerce\Product\ProductIdService;
use R2Packages\Framework\Request;

class ActiveProductController
{
    private ActiveProductService $activeProductService;
    private Request $request;
    private ProductIdService $productIdService;

    public function __construct(
        ActiveProductService $activeProductService,
        Request $request,
        ProductIdService $productIdService,
    ) {
        $this->activeProductService = $activeProductService;
        $this->request = $request;
        $this->productIdService = $productIdService;
    }

    public function index()
    {
        $products = $this->activeProductService->fetch();
        $count = $this->activeProductService->count();
        jsonResponse([
            'message' => 'Products fetched successfully',
            'data' => $products,
            'count' => $count,
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
