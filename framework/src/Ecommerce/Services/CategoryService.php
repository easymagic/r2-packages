<?php 

namespace R2Packages\Framework\Ecommerce\Services;

use Exception;
use R2Packages\Framework\Ecommerce\Entities\CategoryEntity;
use R2Packages\Framework\Ecommerce\Repositories\CategoryRepository;
use R2Packages\Framework\Request;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    protected function validateRequest(Request $request){
        // name is required
        if($request->isEmpty('name')){
            throw new Exception("Name is required!");
        }
        $request->input['name'] = $request->get('name');
        // slug is required
        if($request->isEmpty('slug')){
            $request->input['slug'] = $this->generateSlug($request->get('name'));
        }else{
            $request->input['slug'] = $request->get('slug');
        }

        // description
        if($request->isEmpty('description')){
            $request->input['description'] = $request->get('name');
        }else{
            $request->input['description'] = $request->get('description');
        }

        // is_active
        if($request->isEmpty('is_active')){
            $request->input['is_active'] = $request->get('is_active');
        }else{
            $request->input['is_active'] = 0;
        }

        // created_at
        if($request->isEmpty('created_at')){
            $request->input['created_at'] = date('Y-m-d H:i:s');
        }else{
            $request->input['created_at'] = $request->get('created_at');
        }

        // updated_at
        if($request->isEmpty('updated_at')){
            $request->input['updated_at'] = date('Y-m-d H:i:s');
        }else{
            $request->input['updated_at'] = $request->get('updated_at');
        }
    }

    public function create(Request $request)
    {
        $this->validateRequest($request);
        // parent_id is required
        $category = $this->categoryRepository->save(0, $request->input);
        return $category;
    }

    private function generateSlug($name)
    {
        return preg_replace('/[^a-z0-9-]+/', '-', strtolower($name));
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