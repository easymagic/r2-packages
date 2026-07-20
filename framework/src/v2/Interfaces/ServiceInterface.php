<?php 

namespace R2Packages\Framework\v2\Interfaces;

use R2Packages\Framework\Request;

interface ServiceInterface
{
    public function fetch(Request $request,RepositoryInterface $repository);
    public function fetchAll(Request $request,RepositoryInterface $repository);
    public function count(Request $request,RepositoryInterface $repository);
    public function sum(Request $request,RepositoryInterface $repository);
    public function find(Request $request,RepositoryInterface $repository);
    public function validateCreate(Request $request); // return data to be created
    public function validateUpdate(Request $request); // return data to be updated
    /**
     * Create a record
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function create($data,RepositoryInterface $repository);
    /**
     * Update a record
     * @param array $data
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function update($data,RepositoryInterface $repository);
    public function delete(RepositoryInterface $repository);
}