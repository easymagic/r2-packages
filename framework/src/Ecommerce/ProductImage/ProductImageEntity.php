<?php 

namespace R2Packages\Framework\Ecommerce\ProductImage;

use R2Packages\Framework\Ecommerce\Product\ProductEntity;

class ProductImageEntity
{
    public $id = 0;
    public $product_id = 0;
    public $image_url = '';
    public $alt_text = '';
    public $sort_order = 0;
    public $is_primary = 0;
    public $created_at = '';
    public $updated_at = '';
    public $is_active = 1;

    public function __construct($data = [])
    {
        setAttributes($this, $data);

        if (empty($this->created_at)){
            $this->created_at = date('Y-m-d H:i:s');
        }
        if (empty($this->updated_at)){
            $this->updated_at = date('Y-m-d H:i:s');
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
}
