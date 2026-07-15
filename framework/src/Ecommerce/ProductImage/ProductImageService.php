<?php

namespace R2Packages\Framework\Ecommerce\ProductImage;

use Exception;
use R2Packages\Framework\Ecommerce\Product\ProductEntity;
use R2Packages\Framework\Ecommerce\Product\ProductIdService;
use R2Packages\Framework\FileUploadService;
use R2Packages\Framework\Request;

class ProductImageService
{
    protected ProductImageRepository $productImageRepository;
    private FileUploadService $fileUploadService;
    private ProductIdService $productIdService;
    private ProductImageIdService $productImageIdService;

    public function __construct(
        ProductImageRepository $productImageRepository,
        FileUploadService $fileUploadService,
        ProductIdService $productIdService,
        ProductImageIdService $productImageIdService
    ) {
        $this->productImageRepository = $productImageRepository;
        $this->fileUploadService = $fileUploadService;
        $this->productIdService = $productIdService;
        $this->productImageIdService = $productImageIdService;

        $this->productImageRepository->filterByProductId($this->productIdService->getProduct()->id);
    }

    protected function validateRequest(Request $request, ProductEntity $product)
    {

        if ($request->isEmpty('image_url')) {
            throw new Exception("Image URL is required!");
        }
        $uploadedFile = $this->fileUploadService->uploadFile($request->get('image_url'), 'product_images');
        if ($uploadedFile) {
            $request->input['image_url'] = $uploadedFile;
        }

        if ($request->isEmpty('alt_text')) {
            $request->input['alt_text'] = $product->name;
        } else {
            $request->input['alt_text'] = $request->get('alt_text');
        }
        $request->input['alt_text'] = $request->get('alt_text');

        if ($request->isEmpty('sort_order')) {
            $request->input['sort_order'] = 0;
        } else {
            $request->input['sort_order'] = $request->get('sort_order');
        }

        if ($request->isEmpty('is_primary')) {
            $request->input['is_primary'] = 0;
        } else {
            $request->input['is_primary'] = 1;
        }
        $request->input['is_primary'] = $request->get('is_primary');

        if ($request->isEmpty('is_active')) {
            $request->input['is_active'] = 0;
        } else {
            $request->input['is_active'] = 1;
        }

        $request->input['product_id'] = $product->id;
        $request->input['updated_at'] = date('Y-m-d H:i:s');
    }

    public function create(Request $request)
    {
        $product = $this->productIdService->getProduct();
        $this->validateRequest($request, $product);
        $productImage = $this->productImageRepository->save(0, $request->input);
        return $productImage;
    }

    public function update(Request $request)
    {
        $product = $this->productIdService->getProduct();
        $productImage = $this->productImageIdService->getProductImage();
        $this->validateRequest($request, $product);
        $productImage = $this->productImageRepository->save($productImage->id, $request->input);
        return $productImage;
    }

    public function delete()
    {
        $productImage = $this->productImageIdService->getProductImage();
        $this->productImageRepository->delete($productImage->id);
        return true;
    }

    function fetch(){
        return $this->productImageRepository->fetch();
    }

    function fetchAll(){
        return $this->productImageRepository->fetchAll();
    }

    function count(){
        return $this->productImageRepository->count();
    }

    function one(){
        return $this->productImageIdService->getProductImage();
    }
}
