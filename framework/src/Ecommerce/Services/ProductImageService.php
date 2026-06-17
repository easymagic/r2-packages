<?php

namespace R2Packages\Framework\Ecommerce\Services;

use Exception;
use R2Packages\Framework\Ecommerce\Entities\ProductEntity;
use R2Packages\Framework\Ecommerce\Entities\ProductImageEntity;
use R2Packages\Framework\Ecommerce\Repositories\ProductImageRepository;
use R2Packages\Framework\FileUploadService;
use R2Packages\Framework\Request;

class ProductImageService
{
    private ProductImageRepository $productImageRepository;
    private FileUploadService $fileUploadService;

    public function __construct(ProductImageRepository $productImageRepository, FileUploadService $fileUploadService)
    {
        $this->productImageRepository = $productImageRepository;
        $this->fileUploadService = $fileUploadService;
    }

    protected function validateRequest(Request $request, ProductEntity $product){

        if($request->isEmpty('image_url')){
            throw new Exception("Image URL is required!");
        }
        $request->input['image_url'] = $this->fileUploadService->uploadFile($request->get('image_url'), 'product_images');

        if($request->isEmpty('alt_text')){
            $request->input['alt_text'] = $product->name;
        }else{
            $request->input['alt_text'] = $request->get('alt_text');
        }
        $request->input['alt_text'] = $request->get('alt_text');

        if($request->isEmpty('sort_order')){
            $request->input['sort_order'] = 0;
        }else{
            $request->input['sort_order'] = $request->get('sort_order');
        }

        if($request->isEmpty('is_primary')){
            $request->input['is_primary'] = 0;
        }else{
            $request->input['is_primary'] = 1;
        }
        $request->input['is_primary'] = $request->get('is_primary');

        if($request->isEmpty('is_active')){
            $request->input['is_active'] = 0;
        }else{
            $request->input['is_active'] = 1;
        }

        $request->input['product_id'] = $product->id;
        $request->input['updated_at'] = date('Y-m-d H:i:s');

    }

    public function create(Request $request, ProductEntity $product)
    {
        $this->validateRequest($request, $product);
        $productImage = $this->productImageRepository->save(0, $request->input);
        return $productImage;
    }

    public function update(Request $request, ProductImageEntity $productImage)
    {
        $this->validateRequest($request, $productImage->product);
        $productImage = $this->productImageRepository->save($productImage->id, $request->input);
        return $productImage;
    }

    public function delete(ProductImageEntity $productImage)
    {
        $this->productImageRepository->delete($productImage->id);
        return true;
    }
}