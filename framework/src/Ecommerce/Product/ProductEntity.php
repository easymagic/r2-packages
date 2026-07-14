<?php

namespace R2Packages\Framework\Ecommerce\Product;

use R2Packages\Framework\Ecommerce\Category\CategoryEntity;
use R2Packages\Framework\Ecommerce\Category\CategoryRepository;
use R2Packages\Framework\Ecommerce\ProductImage\ProductImageRepository;
use R2Packages\Framework\BaseUser\BaseUserEntity;
use R2Packages\Framework\BaseUser\BaseUserRepository;

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

    public CategoryEntity $categoryEntity;
    public BaseUserEntity $userEntity;

    public $productImages = [];

    public function __construct(
        CategoryEntity $categoryEntity,
        BaseUserEntity $userEntity,
        $productImages = [],
        $data = []
    ) {
        setAttributes($this, $data);

        $this->categoryEntity = $categoryEntity;
        $this->userEntity = $userEntity;
        $this->productImages = $productImages;

        if (empty($this->created_at)) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        if (empty($this->updated_at)) {
            $this->updated_at = date('Y-m-d H:i:s');
        }
        if (empty($this->is_active)) {
            $this->is_active = 1;
        }
    }

    public function newInstance(CategoryEntity $categoryEntity, BaseUserEntity $userEntity, $productImages = [], $data = [])
    {
        return new self(
            $categoryEntity,
            $userEntity,
            $productImages,
            $data
        );
    }

    public function isEmpty()
    {
        return empty($this->id);
    }
}
