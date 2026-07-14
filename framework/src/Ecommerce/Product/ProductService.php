<?php 

namespace R2Packages\Framework\Ecommerce\Product;

use Exception;
use R2Packages\Framework\BaseUser\BaseUserEntity;
use R2Packages\Framework\BaseUser\UserIdService;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\AuthUserService;

class ProductService
{
    private ProductRepository $productRepository;
    private UserIdService $userIdService;

    public function __construct(ProductRepository $productRepository,UserIdService $userIdService)
    {
        $this->productRepository = $productRepository;
        $this->userIdService = $userIdService;
        $user = $userIdService->getUser();
        $this->productRepository->filterByUserId($user->id);
    }

    function fetch(){
        return $this->productRepository->fetch();
    }

    function fetchAll(){
        return $this->productRepository->fetch();
    }

    protected function validateRequest(Request $request){

        $user = $this->userIdService->getUser();

        if($request->isEmpty('name')){
            throw new Exception("Name is required!");
        }
        $request->input['name'] = $request->get('name');

        if($request->isEmpty('description')){
            throw new Exception("Description is required!");
        }
        $request->input['description'] = $request->get('description');

        if($request->isEmpty('price')){
            throw new Exception("Price is required!");
        }
        $request->input['price'] = $request->get('price');

        if($request->isEmpty('compare_at_price')){
            throw new Exception("Compare at price is required!");
        }
        $request->input['compare_at_price'] = $request->get('compare_at_price');

        if($request->isEmpty('currency')){
            throw new Exception("Currency is required!");
        }
        $request->input['currency'] = $request->get('currency');

        if($request->isEmpty('stock_qty')){
            throw new Exception("Stock quantity is required!");
        }
        $request->input['stock_qty'] = $request->get('stock_qty');

        if($request->isEmpty('is_active')){
            $request->input['is_active'] = 0;
        }else{
            $request->input['is_active'] = $request->get('is_active');
        }  

        $request->input['updated_at'] = date('Y-m-d H:i:s');

        if($request->isEmpty('category_id')){
            throw new Exception("Category ID is required!");
        }
        $request->input['category_id'] = $request->get('category_id');

        $request->input['user_id'] = $user->id;

        if($request->isEmpty('sku')){
            $request->input['sku'] = $this->generateSKU($request->get('name'));
        }else{
            $request->input['sku'] = $request->get('sku');
        }
        
        if($request->isEmpty('slug')){
            $request->input['slug'] = $this->generateSlug($request->get('name'));
        }else{
            $request->input['slug'] = $request->get('slug');
        }
    }

    protected function generateSlug($name)
    {
        return preg_replace('/[^a-z0-9-]+/', '-', strtolower($name));
    }

    protected function generateSKU($name)
    {
        return strtoupper(substr($name, 0, 3)) . '-' . rand(100000, 999999);
    }

    public function create(Request $request)
    {
        $request->input["created_at"] = date('Y-m-d H:i:s');
        $this->validateRequest($request);
        $product = $this->productRepository->save(0, $request->input);
        return $product;
    }

    public function update(Request $request, ProductEntity $product)
    {
        $owner = $this->userIdService->getUser();
        if($owner->id !== $product->user_id){
            throw new Exception("You are not authorized to update this product!");
        }
        $this->validateRequest($request);
        $product = $this->productRepository->save($product->id, $request->input);
        return $product;
    }

    public function delete(ProductEntity $product)
    {
        $owner = $this->userIdService->getUser();
        if($owner->id !== $product->user_id){
            throw new Exception("You are not authorized to delete this product!");
        }
        $this->productRepository->delete($product->id);
        return true;
    }
}
