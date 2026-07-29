<?php 

namespace R2Packages\Framework\Ecommerce\ProductImage;

use Exception;
use R2Packages\Framework\Request;

class ProductImageIdService
{
    private Request $request;
    private ProductImageRepository $productImageRepository;

    public function __construct(Request $request, ProductImageRepository $productImageRepository)
    {
        $this->request = $request;
        $this->productImageRepository = $productImageRepository;
    }

    public function getProductImage()
    {
        if ($this->request->isEmpty('product_image_id')) {
            throw new Exception("Product image ID is required!");
        }
        $productImage = $this->productImageRepository->find($this->request->get('product_image_id'));
        if ($productImage->isEmpty()) {
            throw new Exception("Product image not found!");
        }
        return $productImage;
    }
}
