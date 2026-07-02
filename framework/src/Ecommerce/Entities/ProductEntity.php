<?php

namespace R2Packages\Framework\Ecommerce\Entities;

use R2Packages\Framework\Ecommerce\Repositories\CategoryRepository;
use R2Packages\Framework\Ecommerce\Repositories\ProductImageRepository;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Repositories\BaseUserRepository;

class ProductEntity
{
    public $id = 0;
    public $category_id = 0;
    public $user_id = 0;
    public $sku = '';
    public $slug = '';
    public $name = '';
    public $description = '';
    public $price = 0.00;
    public $compare_at_price = 0;
    public $currency = 'NGN';
    public $stock_qty = 0;
    public $is_active = 1;
    public $created_at = '';
    public $updated_at = '';

    public CategoryEntity $category;
    public BaseUserEntity $user;

    private CategoryRepository $categoryRepository;
    private BaseUserRepository $userRepository;
    private ProductImageRepository $productImageRepository;

    public $productImages = [];



    public function __construct(
        CategoryRepository $categoryRepository,
        BaseUserRepository $userRepository,
        ProductImageRepository $productImageRepository,
        $data = []
    ) {
        setAttributes($this, $data);

        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
        $this->productImageRepository = $productImageRepository;

        if (empty($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        if (empty($this->updated_at)) {
            $this->updated_at = date('Y-m-d H:i:s');
        }
        if (empty($this->is_active)) {
            $this->is_active = 1;
        }
        $this->category = $this->categoryRepository->find($this->category_id);
        $this->user = $this->userRepository->find($this->user_id);
        $this->productImages = $this->productImageRepository->filterByProductId($this->id)->fetchAll();
    }

    public function newInstance($data = [])
    {
        return new self(
            $this->categoryRepository,
            $this->userRepository,
            $this->productImageRepository,
            $data
        );
    }

    public function isEmpty()
    {
        return empty($this->id);
    }

}
