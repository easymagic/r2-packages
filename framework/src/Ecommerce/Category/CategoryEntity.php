<?php 

namespace R2Packages\Framework\Ecommerce\Category;

class CategoryEntity
{
    public $id;
    public $parent_id;
    public $slug;
    public $name;
    public $description = null;
    public $is_active = true;
    public $sort_order = 0;
    public $created_at = null;
    public $updated_at = null;

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
            $this->is_active = true;
        }

        if (empty($this->sort_order)){
            $this->sort_order = 0;
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