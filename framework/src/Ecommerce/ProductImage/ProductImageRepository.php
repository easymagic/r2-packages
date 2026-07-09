<?php

namespace R2Packages\Framework\Ecommerce\ProductImage;

use R2Packages\Framework\Ecommerce\Product\ProductIdService;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class ProductImageRepository
{
    private DbRepository $dbRepository;
    private Request $request;
    private ProductIdService $productIdService;
    private ProductImageEntity $productImageEntity;

    private $sql = '';
    private $params = [];

    public function __construct(
        DbRepository $dbRepository,
        Request $request,
        ProductIdService $productIdService,
        ProductImageEntity $productImageEntity
    ) {
        $this->dbRepository = $dbRepository;
        $this->request = $request;
        $this->productIdService = $productIdService;
        $this->productImageEntity = $productImageEntity;
        $this->sql = "SELECT * FROM product_images WHERE 1=1";
        $this->params = [];
        $this->commonFilters();
    }

    function commonFilters()
    {
        $this->filterByProductId($this->productIdService->getProduct()->id);
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

    public function newInstance()
    {
        return new self($this->dbRepository, $this->request, $this->productIdService, $this->productImageEntity);
    }

    /**
     * @param array $data
     * @return ProductImageEntity
     */
    public function hydrate($data)
    {
        $productImage = $this->productImageEntity->newInstance($data);
        $productImage->setProduct($this->productIdService->getProduct());
        return $productImage;
    }

    public function filterByProductId($productId)
    {
        $this->sql .= " AND product_id = ?";
        $this->params[] = $productId;
        return $this;
    }

    public function fetchAll()
    {
        $results = $this->dbRepository->fetchAll($this->sql, $this->params);
        return array_map(function ($result) {
            return $this->hydrate($result);
        }, $results);
    }

    public function count()
    {
        return $this->dbRepository->count($this->sql, $this->params);
    }

    public function find($id)
    {
        $result = $this->dbRepository->fetchOne("SELECT * FROM product_images WHERE id = ?", [$id]);
        return $this->hydrate($result);
    }

    public function save($id, $data)
    {
        if ($id > 0) {
            $this->dbRepository->update("product_images", $data, ["id" => $id]);
            return $this->find($id);
        }
    }

    public function delete($id)
    {
        $this->dbRepository->delete("product_images", ["id" => $id]);
        return true;
    }
}
