<?php 

namespace R2Packages\Framework\Ecommerce\Category;

use Exception;
use R2Packages\Framework\Ecommerce\Category\Commands\CreateCategoryCommand;
use R2Packages\Framework\Request;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
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


    public function update(Request $request, CategoryEntity $category)
    {
        $this->validateRequest($request);
        $category = $this->categoryRepository->save($category->id, $request->input);
        return $category;
    }

    public function delete(CategoryEntity $category)
    {
        $this->categoryRepository->delete($category->id);
        return true;
    }


}