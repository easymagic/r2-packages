<?php 

namespace R2Packages\Framework\Ecommerce\Cart;

class CartEntity
{
    public $id;
    public $cart_session_id;
    public $product_id;
    public $quantity;
    public $created_at;
    public $updated_at;


    public function __construct($data){
        setAttributes($this, $data);
    }

    function isEmpty(){
        return empty($this->id);
    }
}