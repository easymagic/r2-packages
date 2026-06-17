<?php

namespace R2Packages\Framework\Ecommerce\Repositories\Filters;

use R2Packages\Framework\Ecommerce\Entities\ProductEntity;
use R2Packages\Framework\Ecommerce\Repositories\CategoryRepository;
use R2Packages\Framework\Ecommerce\Repositories\ProductRepository;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class ActiveProductRepository extends ProductRepository
{
    public function __construct(
        DbRepository $dbRepository,
        Request $request,
        ProductEntity $productEntity,
        CategoryRepository $categoryRepository,
        BaseUserRepository $userRepository
    ) {
        parent::__construct($dbRepository, $request, $productEntity, $categoryRepository, $userRepository);
    }


    function commonFilters()
    {
        parent::commonFilters();
        $this->filterByIsActive(1);
    }
}
