<?php

namespace R2Packages\Framework\v2\Interfaces;

interface RepositoryInterface
{
    public function filter();
    public function fetch();
    /**
     * Fetch a record by a field and value
     * @param string $field
     * @param string $value
     * @return mixed
     */
    public function fetchBy($field,$value);
    public function fetchAll();
    public function count();
    /**
     * Sum a field
     * @param string $field
     * @return int
     */
    public function sum($field);
    /**
     * Find a record
     * @param int $id
     * @return mixed
     */
    public function find($id);
    /**
     * Save a record
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function save($id,$data);

    /**
     * Delete a record
     * @param int $id
     * @return bool
     */
    public function delete($id);
}