<?php

namespace R2Packages\Framework\Ecommerce\Category\Filters;

use R2Packages\Framework\Ecommerce\Category\CategoryRepository;
use R2Packages\Framework\Ecommerce\Category\CategoryService;

class ActiveCategoryService extends CategoryService
{
    public function __construct(CategoryRepository $categoryRepository)
    {
        $categoryRepository->filterByIsActive(1);
        parent::__construct($categoryRepository);
    }

}
