<?php

namespace R2Packages\Framework\Infrastructure;

use PDO;
use PDOException;
use R2Packages\Framework\Application\DbConnectionServiceInterface;
use R2Packages\Framework\Application\DbServiceInterface;

class DbService implements DbServiceInterface
{

    private DbConnectionServiceInterface $dbConnectionService;

    public function __construct(DbConnectionServiceInterface $dbConnectionService)
    {
        $this->dbConnectionService = $dbConnectionService;
    }


    public function query(string $sql, array $params = [])
    {
        $stmt = $this->dbConnectionService->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll(string $sql, array $params = [])
    {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne(string $sql, array $params = [])
    {
        $records =  $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
        if (!$records) {
            $records = [];
        }
        return $records;
    }

    public function fetchColumn(string $sql, array $params = [])
    {
        return $this->query($sql, $params)->fetchColumn();
    }

    public function execute(string $sql, array $params = [])
    {
        return $this->query($sql, $params);
    }

    public function lastInsertId()
    {
        return $this->dbConnectionService->getConnection()->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->dbConnectionService->getConnection()->beginTransaction();
    }


    public function commit()
    {
        return $this->dbConnectionService->getConnection()->commit();
    }

    public function rollBack()
    {
        return $this->dbConnectionService->getConnection()->rollBack();
    }

    public function insert(string $table, array $data = [])
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $valuesPlaceHolders = array_fill(0, count($values), '?');
        $sql = "INSERT INTO $table (" . implode(',', $keys) . ") VALUES (" . implode(',', $valuesPlaceHolders) . ")";
        // dd($sql,$values);
        $result = $this->execute($sql, $values);
        if ($result) {
            return $this->lastInsertId();
        }
        return false;
    }

    public function update(string $table, array $data = [], array $where = [])
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $valuesPlaceHolders = [];
        foreach ($keys as $key) {
            $valuesPlaceHolders[] = "$key = ?";
        }
        $whereString = " WHERE 1=1 ";
        foreach ($where as $key => $value) {
            $whereString .= " AND $key = ?";
            $values[] = $value;
        }
        $sql = "UPDATE $table SET " . implode(',', $valuesPlaceHolders) . $whereString;
        // dd($sql,$values,$errors);
        return $this->execute($sql, $values);
    }

    public function delete(string $table, array $where = [])
    {
        $comparison = 'AND';
        $values = [];
        $valuesPlaceHolders = [];
        foreach ($where as $key => $value) {
            $valuesPlaceHolders[] = "$key = ?";
            $values[] = $value;
        }
        $sql = "DELETE FROM $table WHERE " . implode(" $comparison ", $valuesPlaceHolders);
        return $this->execute($sql, $values);
    }

    public function save(string $table, array $data = [])
    {
        $idKeys = ['id'];
        foreach ($idKeys as $idKey) {
            if (isset($data[$idKey]) && $data[$idKey] > 0) {
                $check = "SELECT * FROM $table WHERE $idKey = ?";
                $check = dbFetchOne($check, [$data[$idKey]], $errors);
                if ($check) {
                    dbUpdate($table, $data, [$idKey => $data[$idKey]], $errors);
                }
                return $data[$idKey];
            }
        }
        return $this->insert($table, $data);
    }

    public function paginate(string $sql, int $size, array $params = [])
    {
        $page = $_REQUEST['page'] ?? 1;
        $offset = ($page - 1) * $size;
        $sql .= " LIMIT $size";
        $sql .= " OFFSET $offset";
        return $this->fetchAll($sql, $params);
    }

    public function count(string $sql, array $params = [])
    {
        $sqlCount = "SELECT COUNT(*) FROM ($sql) AS c";
        return $this->fetchColumn($sqlCount, $params);            
    }

    public function sum(string $sql, string $column, array $params = [])
    {
        $sqlSum = "SELECT SUM($column) FROM ($sql) AS c";
        return $this->fetchColumn($sqlSum, $params);            
    }
}
