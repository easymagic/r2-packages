<?php

namespace R2Packages\Framework\Ports;

use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

abstract class AbstractRepositoryPort
{
    protected DbRepository $dbRepository;
    protected PaginationMetta $paginationMetta;
    protected Request $request;

    protected $table = '';
    protected $sql = '';
    protected $params = [];
    protected $data = [];

    public function __construct(
        DbRepository $dbRepository,
        PaginationMetta $paginationMetta,
        Request $request
    ) {
        $this->dbRepository = $dbRepository;
        $this->paginationMetta = $paginationMetta;
        $this->request = $request;
        $this->data = $request->data;
        $this->applyCommonFilters();
    }

    /**
     * Hydrate the data into the entity
     * @param array $data
     * @return mixed
     */
    abstract protected function hydrate($data);


    /**
     * Apply common filters to the SQL and parameters
     */
    abstract protected function applyCommonFilters();

    function fetch() {
        $rows = $this->dbRepository->fetchAll($this->sql, $this->params);
        return array_map([$this, 'hydrate'], $rows);
    }

    function fetchAll() {
        $rows = $this->dbRepository->fetchAll($this->sql);
        return array_map([$this, 'hydrate'], $rows);
    }

    function count() {
       return $this->dbRepository->count($this->sql);
    }

    /**
     * Sum the column by the SQL and parameters
     * @param string $column
     * @return int
     */
    function sum($column) {
        return $this->dbRepository->sum($this->sql, $column, $this->params);
    }

    /**
     * Find the entity by id
     * @param int $id
     * @return mixed
     */
    function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->dbRepository->fetchOne($sql, [$id]);
        return $this->hydrate($result);
    }

    /**
     * Save the entity
     * @param int $id
     * @param array $data
     * @return mixed
     */
    function save($id, $data) {
        if ($id > 0) {
            $this->dbRepository->update($this->table, $data, ["id" => $id]);
            return $this->find($id);
        } else {
            $this->dbRepository->insert($this->table, $data);
            return $this->find($this->dbRepository->lastInsertId());
        }
    }

    /**
     * Delete the entity by id
     * @param int $id
     * @return bool
     */
    function delete($id) {
        $this->dbRepository->delete($this->table, ["id" => $id]);
        return true;
    }
}
