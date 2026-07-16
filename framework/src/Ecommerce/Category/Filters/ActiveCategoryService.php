<?php

namespace R2Packages\Framework\Ecommerce\Category\Filters;

use R2Packages\Framework\Ecommerce\Category\CategoryIdService;
use R2Packages\Framework\Ecommerce\Category\CategoryRepository;
use R2Packages\Framework\Ecommerce\Category\CategoryService;

class ActiveCategoryService extends CategoryService
{
    public function __construct(
        CategoryRepository $categoryRepository,
        CategoryIdService $categoryIdService
    ) {
        $categoryRepository->filterByIsActive(CategoryRepository::STATUS_ACTIVE);
        parent::__construct($categoryRepository, $categoryIdService);
    }
}
