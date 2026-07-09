<?php 

namespace R2Packages\Framework\Ecommerce\ProductImage;

use R2Packages\Framework\Ecommerce\Product\ProductIdService;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class ActiveProductImageRepository extends ProductImageRepository
{
    public function __construct(DbRepository $dbRepository, Request $request, ProductIdService $productIdService, ProductImageEntity $productImageEntity)
    {
        parent::__construct($dbRepository, $request, $productIdService, $productImageEntity);
    }

    function commonFilters()
    {
        parent::commonFilters();
        $this->filterByIsActive(1);
    }
}
