<?php

namespace R2Packages\Framework\Ecommerce\Product;

use R2Packages\Framework\Ecommerce\Category\CategoryRepository;
use R2Packages\Framework\BaseUser\BaseUserRepository;
use R2Packages\Framework\Ecommerce\ProductImage\ProductImageRepository;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class ProductRepository
{
    private DbRepository $dbRepository;
    private Request $request;
    private ProductEntity $productEntity;

    private CategoryRepository $categoryRepository;
    private BaseUserRepository $userRepository;
    private ProductImageRepository $productImageRepository;

    private $sql = '';
    private $params = [];
    protected $size = 10;

    public function __construct(
        DbRepository $dbRepository,
        Request $request,
        ProductEntity $productEntity,
        CategoryRepository $categoryRepository,
        BaseUserRepository $userRepository,
        ProductImageRepository $productImageRepository
    ) {
        $this->productImageRepository = $productImageRepository;
        $this->dbRepository = $dbRepository;
        $this->request = $request;
        $this->productEntity = $productEntity;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
        $this->sql = "SELECT p.id, p.category_id, p.user_id, p.sku, p.slug, p.name, p.description, p.price, p.compare_at_price, p.currency, p.stock_qty, p.is_active, p.created_at, p.updated_at, c.name as category_name, u.name as user_name FROM products as p LEFT JOIN categories as c ON p.category_id = c.id LEFT JOIN users as u ON p.user_id = u.id WHERE 1=1";
        $this->params = [];
        $this->commonFilters();
    }

    function commonFilters()
    {
        if ($this->request->isEmpty('category_id')) {
            $this->filterByCategoryId($this->request->get('category_id'));
        }
        if ($this->request->isEmpty('user_id')) {
            $this->filterByUserId($this->request->get('user_id'));
        }
        if ($this->request->isEmpty('search')) {
            $this->filterBySearch($this->request->get('search'));
        }
        if (!$this->request->isEmpty('is_active') && $this->request->get('is_active') == "yes") {
            $this->filterByIsActive(1);
        }
        if (!$this->request->isEmpty('is_active') && $this->request->get('is_active') == "no") {
            $this->filterByIsActive(0);
        }

        if (!$this->request->isEmpty('order_by') && $this->request->get('order_by') == "a-z") {
            $this->filterByAZ();
        }
        if (!$this->request->isEmpty('order_by') && $this->request->get('order_by') == "z-a") {
            $this->filterByZA();
        }
        if (!$this->request->isEmpty('order_by') && $this->request->get('order_by') == "newest") {
            $this->filterByNewest();
        }
        if (!$this->request->isEmpty('order_by') && $this->request->get('order_by') == "price-low-high") {
            $this->filterByPriceLowHigh();
        }
        if (!$this->request->isEmpty('order_by') && $this->request->get('order_by') == "price-high-low") {
            $this->filterByPriceHighLow();
        }

        $this->filterByIdDescending();
    }

    function filterByAZ()
    {
        $this->sql .= " ORDER BY p.name ASC";
        return $this;
    }

    function filterByZA()
    {
        $this->sql .= " ORDER BY p.name DESC";
        return $this;
    }

    function filterByNewest()
    {
        $this->sql .= " ORDER BY p.created_at DESC";
        return $this;
    }

    function filterByPriceLowHigh()
    {
        $this->sql .= " ORDER BY p.price ASC";
        return $this;
    }

    function filterByPriceHighLow()
    {
        $this->sql .= " ORDER BY p.price DESC";
        return $this;
    }

    function filterByIdDescending()
    {
        $this->sql .= " ORDER BY p.id DESC";
        return $this;
    }

    public function newInstance()
    {
        return new self(
            $this->dbRepository,
            $this->request,
            $this->productEntity,
            $this->categoryRepository,
            $this->userRepository,
            $this->productImageRepository
        );
    }

    /**
     * Hydrate the product data
     * @param array $data
     * @return ProductEntity
     */
    public function hydrate($data)
    {
        $categoryEntity = $this->categoryRepository->find($data['category_id']);
        $userEntity = $this->userRepository->find($data['user_id']);
        $productImages = $this->productImageRepository->filterByProductId($data['id'])->fetchAll();
        $product = $this->productEntity->newInstance($categoryEntity, $userEntity, $productImages, $data);
        return $product;
    }

    public function filterBySearch($search)
    {
        $this->sql .= " AND (p.name LIKE ? OR p.sku LIKE ? OR p.slug LIKE ? OR c.name LIKE ? OR c.slug LIKE ?)";
        $this->params[] = "%$search%";
        $this->params[] = "%$search%";
        $this->params[] = "%$search%";
        $this->params[] = "%$search%";
        $this->params[] = "%$search%";
        return $this;
    }

    public function filterByCategoryId($categoryId)
    {
        $this->sql .= " AND p.category_id = ?";
        $this->params[] = $categoryId;
        return $this;
    }

    public function filterByUserId($userId)
    {
        $this->sql .= " AND p.user_id = ?";
        $this->params[] = $userId;
        return $this;
    }

    public function filterByIsActive($isActive)
    {
        $this->sql .= " AND p.is_active = ?";
        $this->params[] = $isActive;
        return $this;
    }

    public function fetchAll()
    {
        $results = $this->dbRepository->fetchAll($this->sql, $this->params);
        return array_map(function ($result) {
            return $this->hydrate($result);
        }, $results);
    }

    public function fetch()
    {
        $results = $this->dbRepository->paginate($this->sql, $this->size, $this->params);
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
        $result = $this->dbRepository->fetchOne("SELECT * FROM products WHERE id = ?", [$id]);
        return $this->hydrate($result);
    }

    public function save($id, $data)
    {
        if ($id > 0) {
            $this->dbRepository->update("products", $data, ["id" => $id]);
            return $this->find($id);
        } else {
            $id = $this->dbRepository->insert("products", $data);
            return $this->find($id);
        }
    }

    public function delete($id)
    {
        $this->dbRepository->delete("products", ["id" => $id]);
        return true;
    }
}
