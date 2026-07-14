<?php

namespace R2Packages\Framework\Ecommerce\Product;

use R2Packages\Framework\Ecommerce\Category\CategoryRepository;
use R2Packages\Framework\BaseUser\BaseUserRepository;
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
           $this->filterByUserId(0);
           return;
        }
        if ($authUser->isAdmin()){
            return;
        }
        $this->filterByUserId($authUser->id);
    }
}
