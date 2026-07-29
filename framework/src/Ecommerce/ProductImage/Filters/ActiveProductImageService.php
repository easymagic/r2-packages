<?php

namespace R2Packages\Framework\Ecommerce\ProductImage\Filters;

use R2Packages\Framework\Ecommerce\Product\ProductIdService;
use R2Packages\Framework\Ecommerce\ProductImage\ProductImageIdService;
use R2Packages\Framework\Ecommerce\ProductImage\ProductImageRepository;
use R2Packages\Framework\Ecommerce\ProductImage\ProductImageService;
use R2Packages\Framework\FileUploadService;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class ActiveProductImageService extends ProductImageService
{

    public function __construct(
        ProductImageRepository $productImageRepository,
        FileUploadService $fileUploadService,
        ProductIdService $productIdService,
        ProductImageIdService $productImageIdService
    ) {
        parent::__construct($productImageRepository, $fileUploadService, $productIdService, $productImageIdService);
        $this->productImageRepository->filterByIsActive(ProductImageRepository::IS_ACTIVE);
    }

}
