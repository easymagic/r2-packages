<?php 

namespace R2Packages\Framework\Ecommerce\Repositories;

use R2Packages\Framework\Ecommerce\Entities\CategoryEntity;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class CategoryRepository
{
    private DbRepository $dbRepository;

    private $sql = '';
    private $params = [];
    private Request $request;
    protected $size = 10;

    private CategoryEntity $categoryEntity;

    public function __construct(DbRepository $dbRepository, Request $request, CategoryEntity $categoryEntity)
    {
        $this->dbRepository = $dbRepository;
        $this->request = $request;
        $this->categoryEntity = $categoryEntity;
        $this->sql = "SELECT c.id, c.parent_id, c.slug, c.name, c.description, c.is_active, c.sort_order, c.created_at, c.updated_at, p.name as parent_name FROM categories as c LEFT JOIN categories as p ON c.parent_id = p.id WHERE 1=1";
        $this->params = [];
        $this->commonFilters();
    }

    function commonFilters()
    {
        if($this->request->isEmpty('parent_id')){
            $this->filterByParentId($this->request->get('parent_id'));
        }
        if($this->request->isEmpty('search')){
            $this->filterBySearch($this->request->get('search'));
        }
        if($this->request->isEmpty('is_active') && $this->request->get('is_active') == "yes"){
            $this->filterByIsActive(1);
        }
        if($this->request->isEmpty('is_active') && $this->request->get('is_active') == "no"){
            $this->filterByIsActive(0);
        }

    }

    function newInstance()
    {
        return new self($this->dbRepository, $this->request, $this->categoryEntity);
    }

    function hydrate($data)
    {
        return $this->categoryEntity->newInstance($data);
    }

    function filterByParentId($parentId)
    {
        $this->sql .= " AND c.parent_id = ?";
        $this->params[] = $parentId;
        return $this;
    }

    // filter by search
    function filterBySearch($search)
    {
        $this->sql .= " AND c.name LIKE ? OR c.slug LIKE ? OR p.name LIKE ?";
        $this->params[] = "%$search%";
        $this->params[] = "%$search%";
        $this->params[] = "%$search%";
        return $this;
    }

    function filterByIsActive($isActive)
    {
        $this->sql .= " AND c.is_active = ?";
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

    function find($id)
    {
        $result = $this->dbRepository->fetchOne("SELECT * FROM categories WHERE id = ?", [$id]);
        return $this->hydrate($result);
    }

    function save($id, $data)
    {
        if($id > 0){
            $this->dbRepository->update("categories", $data, ["id" => $id]);
            return $this->find($id);
        }else{
            $id = $this->dbRepository->insert("categories", $data);
            return $this->find($id);
        }
    }

    function delete($id)
    {
        $this->dbRepository->delete("categories", ["id" => $id]);
        return true;
    }


}