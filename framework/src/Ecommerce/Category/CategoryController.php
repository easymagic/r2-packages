<?php

namespace R2Packages\Framework\Ecommerce\Category;

use R2Packages\Framework\Ecommerce\Category\Commands\CreateCategoryCommand;
use R2Packages\Framework\Ecommerce\Category\Commands\UpdateCategoryCommand;
use R2Packages\Framework\Ecommerce\Category\Filters\ActiveCategoryService;
use R2Packages\Framework\Request;

class CategoryController
{
    private CategoryService $categoryService;
    private Request $request;
    private ActiveCategoryService $activeCategoryService;

    private CreateCategoryCommand $createCategoryCommand;
    private UpdateCategoryCommand $updateCategoryCommand;

    public function __construct(
        CategoryService $categoryService,
        Request $request,
        ActiveCategoryService $activeCategoryService,
        CreateCategoryCommand $createCategoryCommand,
        UpdateCategoryCommand $updateCategoryCommand
    ) {
        $this->categoryService = $categoryService;
        $this->request = $request;
        $this->activeCategoryService = $activeCategoryService;
        $this->createCategoryCommand = $createCategoryCommand;
        $this->updateCategoryCommand = $updateCategoryCommand;
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
        $category = $this->categoryService->create($this->createCategoryCommand);
        jsonResponse([
            'message' => 'Category created successfully',
            'data' => $category,
            "success" => true
        ]);
    }

    public function update()
    {
        $category = $this->categoryService->update($this->updateCategoryCommand);
        jsonResponse([
            'message' => 'Category updated successfully',
            'data' => $category,
            "success" => true
        ]);
    }

    public function delete()
    {
        $this->categoryService->delete();
        jsonResponse([
            'message' => 'Category deleted successfully',
            "success" => true
        ]);
    }

    public function get()
    {
        $category = $this->categoryService->one();
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
