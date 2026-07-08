<?php 

namespace R2Packages\Framework\Ecommerce\Category;

use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class ActiveCategoryRepository extends CategoryRepository
{
        public function __construct(DbRepository $dbRepository, Request $request, CategoryEntity $categoryEntity)
        {
            parent::__construct($dbRepository, $request, $categoryEntity);
        }


    function commonFilters()
    {
        parent::commonFilters();
        $this->filterByIsActive(1);
    }
}