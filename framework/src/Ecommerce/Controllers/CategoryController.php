<?php

namespace R2Packages\Framework\Ecommerce\Controllers;

use R2Packages\Framework\Ecommerce\Repositories\CategoryRepository;
use R2Packages\Framework\Ecommerce\Repositories\Filters\ActiveCategoryRepository;
use R2Packages\Framework\Ecommerce\Services\CategoryService;
use R2Packages\Framework\Ecommerce\Services\Ids\CategoryIdService;
use R2Packages\Framework\Request;

class CategoryController
{
    private CategoryService $categoryService;
    private Request $request;
    private CategoryRepository $categoryRepository;
    private CategoryIdService $categoryIdService;
    private ActiveCategoryRepository $activeCategoryRepository;

    public function __construct(
        CategoryService $categoryService,
        Request $request,
        CategoryRepository $categoryRepository,
        CategoryIdService $categoryIdService,
        ActiveCategoryRepository $activeCategoryRepository
    ) {
        $this->categoryService = $categoryService;
        $this->request = $request;
        $this->categoryRepository = $categoryRepository;
        $this->categoryIdService = $categoryIdService;
        $this->activeCategoryRepository = $activeCategoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->fetchAll();
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
        $categories = $this->activeCategoryRepository->fetchAll();
        jsonResponse([
            'message' => 'Active categories fetched successfully',
            'data' => $categories,
            "success" => true
        ]);
    }
}
