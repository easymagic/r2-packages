<?php

namespace R2Packages\Framework\Repositories;

class DbRepository
{
    public function __construct() {}

    public function query($sql, $params = [])
    {
        return dbQuery($sql, $params);
    }

    public function fetchAll($sql, $params = [])
    {
        return dbFetchAll($sql, $params);
    }

    public function fetchOne($sql, $params = [])
    {
        return dbFetchOne($sql, $params);
    }

    public function fetchColumn($sql, $params = [])
    {
        return dbFetchColumn($sql, $params);
    }

    public function execute($sql, $params = [])
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

    public function insert($table, $data = [])
    {
        return dbInsert($table, $data);
    }

    public function update($table, $data = [], $where = [])
    {
        return dbUpdate($table, $data, $where);
    }

    public function delete($table, $where = [])
    {
        return dbDelete($table, $where);
    }

    public function save($table, $data = [])
    {
        return dbSave($table, $data);
    }

    public function paginate($table, $data = [], $where = [])
    {
        return dbPaginate($table, $data, $where);
    }

    public function count($table, $data = [], $where = [])
    {
        return dbCount($table, $data, $where);
    }

    public function sum($table, $data = [], $where = [])
    {
        return dbSum($table, $data, $where);
    }
}
