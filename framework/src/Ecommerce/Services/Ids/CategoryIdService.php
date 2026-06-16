<?php

namespace R2Packages\Framework\Ecommerce\Services\Ids;

use Exception;
use R2Packages\Framework\Ecommerce\Repositories\CategoryRepository;
use R2Packages\Framework\Request;

class CategoryIdService
{
    private Request $request;
    private CategoryRepository $categoryRepository;

    public function __construct(Request $request, CategoryRepository $categoryRepository)
    {
        $this->request = $request;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategory()
    {
        if ($this->request->isEmpty('category_id')) {
            throw new Exception("Category ID is required!");
        }
        $category = $this->categoryRepository->find($this->request->get('category_id'));
        if ($category->isEmpty()) {
            throw new Exception("Category not found!");
        }
        return $category;
    }
}