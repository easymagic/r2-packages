<?php

namespace R2Packages\Framework\v2\Interfaces;

interface RepositoryInterface
{
    /**
     * Filter a record
     * @param array $data
     * @return mixed
     */
    public function filter($data);
    public function fetch();
    /**
     * Fetch a record by a field and value
     * @param string $field
     * @param string $value
     * @return self
     */
    public function fetchBy($field,$value);
    public function fetchAll();
    
    /**
     * Fetch a single record
     * @return mixed
     */
    public function fetchOne();
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

    /**
     * Set the SQL query
     * @param string $sql
     * @return self
     */
    public function setSql($sql);

    
    /**
     * Set the parameters for the SQL query
     * @param array $params
     * @return self
     */
    public function setParams($params);
}