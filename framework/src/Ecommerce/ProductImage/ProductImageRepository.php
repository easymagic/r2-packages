<?php

namespace R2Packages\Framework\Ecommerce\ProductImage;

use R2Packages\Framework\Ecommerce\Product\ProductIdService;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Ports\AbstractRepositoryPort;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class ProductImageRepository extends AbstractRepositoryPort
{
    protected $table = 'product_images';
    protected $sql = 'SELECT * FROM product_images WHERE 1=1';
    private ProductImageEntity $productImageEntity;

    const IS_ACTIVE = 1;

    public function __construct(
        DbRepository $dbRepository,
        Request $request,
        ProductImageEntity $productImageEntity,
        PaginationMetta $paginationMeta,
    ) {
        parent::__construct($dbRepository, $paginationMeta, $request);
        $this->productImageEntity = $productImageEntity;
    }

    protected function applyCommonFilters()
    {
        // $this->filterByProductId($this->productIdService->getProduct()->id);
    }

    /**
     * @param int $isActive
     * @return $this
     */
    function filterByIsActive($isActive)
    {
        $this->sql .= " AND is_active = ?";
        $this->params[] = $isActive;
        return $this;
    }


    /**
     * @param array $data
     * @return ProductImageEntity
     */
    protected function hydrate($data)
    {
        $productImage = $this->productImageEntity->newInstance($data);
        return $productImage;
    }

    /**
     * @param int $productId
     * @return $this
     */
    public function filterByProductId($productId)
    {
        $this->sql .= " AND product_id = ?";
        $this->params[] = $productId;
        return $this;
    }


}
