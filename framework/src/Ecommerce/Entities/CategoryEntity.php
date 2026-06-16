<?php 

namespace R2Packages\Framework\Ecommerce\Entities;

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