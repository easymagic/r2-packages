<?php 

namespace R2Packages\Framework\Ecommerce\Cart;

use R2Packages\Framework\Ports\AbstractRepositoryPort;

class CartRepository extends AbstractRepositoryPort
{
    public function __construct(CartEntity $cartEntity)
    {
        $this->cartEntity = $cartEntity;
    }
}