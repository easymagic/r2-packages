<?php 

namespace R2Packages\Framework\v2\Interfaces;

interface ServiceInterface
{
    public function fetch(RepositoryInterface $repository);
    public function fetchAll(RepositoryInterface $repository);
    public function count(RepositoryInterface $repository);
    public function sum(RepositoryInterface $repository);
    public function find(RepositoryInterface $repository);
    public function create(RepositoryInterface $repository);
    public function update(RepositoryInterface $repository);
    public function delete(RepositoryInterface $repository);
}