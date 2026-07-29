<?php

namespace R2Packages\Framework\Ecommerce\Category\Commands;

use Exception;
use R2Packages\Framework\Request;

class CreateCategoryCommand
{

     private Request $request;

     public function __construct(Request $request)
     {
        $this->request = $request;
     }

    function handle(){
        // parent_id is optional
        if(!$this->request->isEmpty('parent_id')){
            $this->request->input['parent_id'] = $this->request->get('parent_id');
        }else{
            $this->request->input['parent_id'] = 0;
        }
        // name is required
        if($this->request->isEmpty('name')){
            throw new Exception("Name is required!");
        }
        $this->request->input['name'] = $this->request->get('name');
        // slug is required
        if($this->request->isEmpty('slug')){
            $this->request->input['slug'] = $this->generateSlug($this->request->get('name'));
        }else{
            $this->request->input['slug'] = $this->request->get('slug');
        }

        // description
        if($this->request->isEmpty('description')){
            $this->request->input['description'] = $this->request->get('name');
        }else{
            $this->request->input['description'] = $this->request->get('description');
        }

        // is_active
        if($this->request->isEmpty('is_active')){
            $this->request->input['is_active'] = $this->request->get('is_active');
        }else{
            $this->request->input['is_active'] = 0;
        }

        // created_at
        if($this->request->isEmpty('created_at')){
            $this->request->input['created_at'] = date('Y-m-d H:i:s');
        }else{
            $this->request->input['created_at'] = $this->request->get('created_at');
        }

        // updated_at
        if($this->request->isEmpty('updated_at')){
            $this->request->input['updated_at'] = date('Y-m-d H:i:s');
        }else{
            $this->request->input['updated_at'] = $this->request->get('updated_at');
        }

        return $this->request->input;
    }

    /**
     * Generate slug
     * @param string $name
     * @return string
     */
    private function generateSlug($name)
    {
        return preg_replace('/[^a-z0-9-]+/', '-', strtolower($name));
    }
}