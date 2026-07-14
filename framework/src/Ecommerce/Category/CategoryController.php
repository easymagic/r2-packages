<?php

namespace R2Packages\Framework\Ecommerce\Category;

use R2Packages\Framework\Ecommerce\Category\Filters\ActiveCategoryService;
use R2Packages\Framework\Request;

class CategoryController
{
    private CategoryService $categoryService;
    private Request $request;
    private CategoryIdService $categoryIdService;
    private ActiveCategoryService $activeCategoryService;

    public function __construct(
        CategoryService $categoryService,
        Request $request,
        CategoryIdService $categoryIdService,
        ActiveCategoryService $activeCategoryService
    ) {
        $this->categoryService = $categoryService;
        $this->request = $request;
        $this->categoryIdService = $categoryIdService;
        $this->activeCategoryService = $activeCategoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->fetchAll();
        jsonResponse([
            'message' => 'Categories fetched successfully',
            'data' => $categories,
            "success" => true
        ]);
    }

    public function create()
    {
        $category = $this->categoryService->create($this->request);
        jsonResponse([
            'message' => 'Category created successfully',
            'data' => $category,
            "success" => true
        ]);
    }

    public function update()
    {
        $category = $this->categoryIdService->getCategory();
        $category = $this->categoryService->update($this->request, $category);
        jsonResponse([
            'message' => 'Category updated successfully',
            'data' => $category,
            "success" => true
        ]);
    }

    public function delete()
    {
        $category = $this->categoryIdService->getCategory();
        $this->categoryService->delete($category);
        jsonResponse([
            'message' => 'Category deleted successfully',
            "success" => true
        ]);
    }

    public function get()
    {
        $category = $this->categoryIdService->getCategory();
        jsonResponse([
            'message' => 'Category fetched successfully',
            'data' => $category,
            "success" => true
        ]);
    }

    public function getActiveCategories()
    {
        $categories = $this->activeCategoryService->fetchAll();
        jsonResponse([
            'message' => 'Active categories fetched successfully',
            'data' => $categories,
            "success" => true
        ]);
    }
}
