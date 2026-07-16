<?php

namespace R2Packages\Framework\Ecommerce\Category;

use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Ports\AbstractRepositoryPort;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class CategoryRepository extends AbstractRepositoryPort
{
    protected $table = 'categories';
    protected $sql = 'SELECT c.*, p.name as parent_name FROM categories as c LEFT JOIN categories as p ON c.parent_id = p.id WHERE 1=1';
    protected $params = [];
    protected $size = 10;
    protected $data = [];

    private CategoryEntity $categoryEntity;

    public function __construct(
        DbRepository $dbRepository,
        PaginationMetta $paginationMetta,
        Request $request,
        CategoryEntity $categoryEntity
    ) {
        parent::__construct($dbRepository, $paginationMetta, $request);
        $this->categoryEntity = $categoryEntity;
    }

    protected function applyCommonFilters()
    {
        if ($this->request->isEmpty('parent_id')) {
            $this->filterByParentId($this->request->get('parent_id'));
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
    }

    protected function hydrate($data)
    {
        return $this->categoryEntity->newInstance($data);
    }

    /**
     * Filter by parent id
     * @param int $parentId
     * @return $this
     */
    function filterByParentId($parentId)
    {
        $this->sql .= " AND c.parent_id = ?";
        $this->params[] = $parentId;
        return $this;
    }

    /**
     * Filter by search
     * @param string $search
     * @return $this
     */
    function filterBySearch($search)
    {
        $this->sql .= " AND c.name LIKE ? OR c.slug LIKE ? OR p.name LIKE ?";
        $this->params[] = "%$search%";
        $this->params[] = "%$search%";
        $this->params[] = "%$search%";
        return $this;
    }

    /**
     * Filter by is active
     * @param int $isActive
     * @return $this
     */
    function filterByIsActive($isActive)
    {
        $this->sql .= " AND c.is_active = ?";
        $this->params[] = $isActive;
        return $this;
    }
}
