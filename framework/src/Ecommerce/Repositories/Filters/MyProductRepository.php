<?php

namespace R2Packages\Framework\Ecommerce\Repositories\Filters;

use Exception;
use R2Packages\Framework\Ecommerce\Entities\ProductEntity;
use R2Packages\Framework\Ecommerce\Repositories\CategoryRepository;
use R2Packages\Framework\Ecommerce\Repositories\ProductRepository;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;

class MyProductRepository extends ProductRepository
{

    protected AuthUserService $authUserService;

    public function __construct(
        DbRepository $dbRepository,
        Request $request,
        ProductEntity $productEntity,
        CategoryRepository $categoryRepository,
        BaseUserRepository $userRepository,
        AuthUserService $authUserService
    ) {
        parent::__construct($dbRepository, $request, $productEntity, $categoryRepository, $userRepository);
        $this->authUserService = $authUserService;
    }


    function commonFilters()
    {
        parent::commonFilters();
        $authUser = $this->authUserService->getAuthUser();
        if($authUser->isEmpty()){
           $this->filterByUserId(0); // show no products
           return;
        }
        if ($authUser->isAdmin()){
            return; // admin can see all products
        }
        // show only the user's own products
        $this->filterByUserId($authUser->id);
    }
}
