<?php 
namespace R2Packages\Framework\Domain\User;

interface UserRepositoryInterface
{
    public function findById(int $id);
    public function findByEmail(string $email);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function fetchAll();
    function fetch();
    function count();
}