<?php 
namespace R2Packages\Framework\Application;

interface DbServiceInterface
{
    public function query(string $sql, array $params = []);

    public function fetchAll(string $sql, array $params = []);

    public function fetchOne(string $sql, array $params = []);

    public function fetchColumn(string $sql, array $params = []);

    public function execute(string $sql, array $params = []);

    public function lastInsertId();

    public function beginTransaction();

    public function commit();

    public function rollBack();

    public function insert(string $table, array $data = []);

    public function update(string $table, array $data = [], array $where = []);

    public function delete(string $table, array $where = []);

    public function save(string $table, array $data = []);

    public function paginate(string $sql, int $size, array $params = []);

    public function count(string $sql, array $params = []);

    public function sum(string $sql, string $column, array $params = []);
    
}