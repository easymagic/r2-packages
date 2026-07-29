<?php 

namespace R2Packages\Framework\Ecommerce\Category;

use Exception;
use R2Packages\Framework\Ecommerce\Category\Commands\CreateCategoryCommand;
use R2Packages\Framework\Ecommerce\Category\Commands\UpdateCategoryCommand;
use R2Packages\Framework\Request;

class CategoryService
{
    private CategoryRepository $categoryRepository;
    private CategoryIdService $categoryIdService;

    public function __construct(CategoryRepository $categoryRepository,CategoryIdService $categoryIdService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryIdService = $categoryIdService;
    }

    function fetch(){
        return $this->categoryRepository->fetch();
    }

    function fetchAll(){
        return $this->categoryRepository->fetchAll();
    }

    function count(){
        return $this->categoryRepository->count();
    }

    public function create(CreateCategoryCommand $createCategoryCommand)
    {
        $data = $createCategoryCommand->handle();
        $category = $this->categoryRepository->save(0, $data);
        return $category;
    }


    public function update(UpdateCategoryCommand $updateCategoryCommand)
    {
        $data = $updateCategoryCommand->handle();
        $category = $this->categoryIdService->getCategory();
        $category = $this->categoryRepository->save($category->id, $data);
        return $category;
    }

    public function delete()
    {
        $category = $this->one();
        $this->categoryRepository->delete($category->id);
        return true;
    }

    function one(){
        return $this->categoryIdService->getCategory();
    }


}