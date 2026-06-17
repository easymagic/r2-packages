<?php 

namespace R2Packages\Framework\Ecommerce\Services\Ids;

use Exception;
use R2Packages\Framework\Ecommerce\Repositories\ProductRepository;
use R2Packages\Framework\Request;

class ProductIdService
{
    private Request $request;
    private ProductRepository $productRepository;

    public function __construct(Request $request, ProductRepository $productRepository)
    {
        $this->request = $request;
        $this->productRepository = $productRepository;
    }

    public function getProduct()
    {
        if ($this->request->isEmpty('product_id')) {
            throw new Exception("Product ID is required!");
        }
        $product = $this->productRepository->find($this->request->get('product_id'));
        if ($product->isEmpty()) {
            throw new Exception("Product not found!");
        }
        return $product;
    }
}