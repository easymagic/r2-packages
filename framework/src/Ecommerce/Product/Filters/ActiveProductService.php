<?php

namespace R2Packages\Framework\Ecommerce\Product\Filters;

use R2Packages\Framework\Ecommerce\Product\ProductRepository;

class ActiveProductService
{
    private ProductRepository $productRepository;

    public function __construct(
        ProductRepository $productRepository,
    ) {
        $this->productRepository = $productRepository;
        $this->productRepository->filterByIsActive(ProductRepository::STATUS_ACTIVE);
    }

    public function fetch(){
        return $this->productRepository->fetch();
    }

    public function fetchAll(){
        return $this->productRepository->fetchAll();
    }

    public function count(){
        return $this->productRepository->count();
    }

}
