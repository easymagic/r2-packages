<?php

namespace R2Packages\Framework\Infrastructure;

use R2Packages\Framework\Application\DbServiceInterface;

class DbService implements DbServiceInterface
{
    public function query(string $sql, array $params = [])
    {
        return dbQuery($sql, $params);
    }

    public function fetchAll(string $sql, array $params = [])
    {
        return dbFetchAll($sql, $params);
    }

    public function fetchOne(string $sql, array $params = [])
    {
        return dbFetchOne($sql, $params);
    }

    public function fetchColumn(string $sql, array $params = [])
    {
        return dbFetchColumn($sql, $params);
    }

    public function execute(string $sql, array $params = [])
    {
        return dbExecute($sql, $params);
    }

    public function lastInsertId()
    {
        return dbLastInsertId();
    }

    public function beginTransaction()
    {
        return dbBeginTransaction();
    }


    public function commit()
    {
        return dbCommit();
    }

    public function rollBack()
    {
        return dbRollBack();
    }

    public function insert(string $table, array $data = [])
    {
        return dbInsert($table, $data);
    }

    public function update(string $table, array $data = [], array $where = [])
    {
        return dbUpdate($table, $data, $where);
    }

    public function delete(string $table, array $where = [])
    {
        return dbDelete($table, $where);
    }

    public function save(string $table, array $data = [])
    {
        return dbSave($table, $data);
    }

    public function paginate(string $sql, int $size, array $params = [])
    {
        return dbPaginate($sql, $size, $params);
    }

    public function count(string $sql, array $params = [])
    {
        return dbCount($sql, $params);
    }

    public function sum(string $sql, string $column, array $params = [])
    {
        return dbSum($sql, $column, $params);
    }
}
