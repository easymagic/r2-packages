<?php 

namespace R2Packages\Framework\Ecommerce\Entities;

use R2Packages\Framework\Entities\BaseUserEntity;

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


    public function __construct($data = [])
    {
        setAttributes($this, $data);

        if (empty($this->created_at)){
            $this->created_at = date('Y-m-d H:i:s');
        }
        if (empty($this->updated_at)){
            $this->updated_at = date('Y-m-d H:i:s');
        }
        if (empty($this->is_active)){
            $this->is_active = 1;
        }
    }

    public function newInstance($data = [])
    {
        return new self($data);
    }

    public function isEmpty()
    {
        return empty($this->id);
    }

    public function setCategory(CategoryEntity $category)
    {
        $this->category = $category;
        return $this;
    }

    public function setUser(BaseUserEntity $user)
    {
        $this->user = $user;
        return $this;
    }

}