<?php

use R2Packages\Framework\Connection;

/**
 * Get the database connection instance
 * @return PDO Database connection
 */
function dbConnection() 
{
    global $config;
    // dd($config);
    return Connection::getConnection($config['db']);
}

/**
 * Execute a SQL query with parameters
 * @param string $sql SQL query string
 * @param array $params Query parameters
 * @param array &$errors Array to store any errors
 * @return PDOStatement Query statement
 */
function dbQuery($sql, $params = [], &$errors = [])
{
    try {
        $stmt = dbConnection()->prepare($sql);
        $payload = [
            "sql" => $sql,
            "params" => $params
        ];
        // EventBus::getInstance()->dispatch('OnDbQuery', $payload, $errors);
        // print_r([$sql,$params]);
        $stmt->execute($params);
        
        // Check for statement errors
        // dd($stmt->errorCode());
        // dd($stmt->errorInfo());
        if ($stmt->errorCode() !== '00000') {
            $errorInfo = $stmt->errorInfo();
            // $errors[] = $errorInfo[2] . " in " . $sql;
            // dd("...123....");
        }
        // print_r($errors);
    } catch (PDOException $e) {
        $errors[] = $e->getMessage() . " in " . $sql;
        // dd($errors);
        dbErrors([$e->getMessage() . " in " . $sql]);
        return false;
    }
    return $stmt;
}

function dbErrors($errors = []){
  static $errorCache = [];
  if(count($errors) > 0){
    $errorCache = array_merge($errorCache, $errors);
  }
  return $errorCache;
}

/**
 * Fetch all rows from a SQL query
 * @param string $sql SQL query string
 * @param array $params Query parameters
 * @param array &$errors Array to store any errors
 * @return array Query results
 */
function dbFetchAll($sql, $params = [], &$errors = [])
{
    // dd($sql,$params);
    $results = dbQuery($sql, $params, $errors)->fetchAll(PDO::FETCH_ASSOC);
    return $results;
}

/**
 * Fetch a single row from a SQL query
 * @param string $sql SQL query string
 * @param array $params Query parameters
 * @param array &$errors Array to store any errors
 * @return array|false Query result row or false if not found
 */
function dbFetchOne($sql, $params = [], &$errors = [])
{
    $result = dbQuery($sql, $params, $errors)->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $result = [];
    }
    return $result;
}

/**
 * Fetch a single column value from a SQL query
 * @param string $sql SQL query string
 * @param array $params Query parameters
 * @param array &$errors Array to store any errors
 * @return mixed Column value
 */
function dbFetchColumn($sql, $params = [], &$errors = [])
{
    return dbQuery($sql, $params, $errors)->fetchColumn();
}

/**
 * Execute a SQL query without returning results
 * @param string $sql SQL query string
 * @param array $params Query parameters
 * @param array &$errors Array to store any errors
 * @return PDOStatement Query statement
 */
function dbExecute($sql, $params = [], &$errors = [])
{
    return dbQuery($sql, $params, $errors);
}

/**
 * Get the last inserted ID
 * @return string Last insert ID
 */
function dbLastInsertId()
{
    return dbConnection()->lastInsertId();
}

/**
 * Begin a database transaction
 * @return bool Success
 */
function dbBeginTransaction()
{
    return dbConnection()->beginTransaction();
}

/**
 * Commit a database transaction
 * @return bool Success
 */
function dbCommit()
{
    return dbConnection()->commit();
}

/**
 * Rollback a database transaction
 * @return bool Success
 */
function dbRollBack()
{
    return dbConnection()->rollBack();
}

/**
 * Check if currently in a transaction
 * @return bool True if in transaction
 */
function dbInTransaction()
{
    return dbConnection()->inTransaction();
}

/**
 * Get the PDO connection instance
 * @return PDO Database connection
 */
function dbGetPdo()
{
    return dbConnection();
}

/**
 * Insert a new record into a table
 * @param string $table Table name
 * @param array $data Column data
 * @param array &$errors Array to store any errors
 * @return int|false Insert ID on success, false on failure
 */
function dbInsert($table, $data, &$errors = [])
{
    $payload = [
        "table" => $table,
        "data" => $data
    ];
    // EventBus::getInstance()->dispatch('OnTableInsert', $payload, $errors);
    if (count($errors) > 0) {
        return false;
    }
    $keys = array_keys($data);
    $values = array_values($data);
    $valuesPlaceHolders = array_fill(0, count($values), '?');
    $sql = "INSERT INTO $table (" . implode(',', $keys) . ") VALUES (" . implode(',', $valuesPlaceHolders) . ")";
    // dd($sql,$values);
    $result = dbExecute($sql, $values, $errors);
    if ($result) {
        return dbLastInsertId();
    }
    return false;
}

/**
 * Update records in a table
 * @param string $table Table name
 * @param array $data Column data to update
 * @param array $where Where conditions
 * @param array &$errors Array to store any errors
 * @return PDOStatement Query statement
 */
function dbUpdate($table, $data, $where, &$errors = [])
{
    $payload = [
        "table" => $table,
        "data" => $data,
        "where" => $where
    ];
    // EventBus::getInstance()->dispatch('OnTableUpdate', $payload, $errors);
    if (count($errors) > 0) {
        return false;
    }
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
    return dbExecute($sql, $values, $errors);
}

/**
 * Delete records from a table
 * @param string $table Table name
 * @param array $where Where conditions
 * @param string $comparison Comparison operator (AND/OR)
 * @param array &$errors Array to store any errors
 * @return PDOStatement Query statement
 */
function dbDelete($table, $where = [], $comparison = 'AND', &$errors = [])
{
    $keys = array_keys($where);
    $values = [];
    $valuesPlaceHolders = [];
    foreach ($where as $key => $value) {
        $valuesPlaceHolders[] = "$key = ?";
        $values[] = $value;
    }
    $sql = "DELETE FROM $table WHERE " . implode(" $comparison ", $valuesPlaceHolders);
    return dbExecute($sql, $values, $errors);
}

/**
 * Save data to a table (insert or update)
 * @param string $table Table name
 * @param array $data Column data
 * @param array &$errors Array to store any errors
 * @param array $idKeys Primary key column names
 * @return mixed Insert ID or update result
 */
function dbSave($table, $data, &$errors = [], $idKeys = ['id'])
{
    $payload = [
        "table" => $table,
        "data" => $data
    ];

    
    // EventBus::getInstance()->dispatch('OnTableSave', $payload, $errors);
    if (count($errors) > 0) {
        return false;
    }

    foreach ($idKeys as $idKey) {
        if (isset($data[$idKey]) && $data[$idKey] > 0) {
            $check = "SELECT * FROM $table WHERE $idKey = ?";
            $check = dbFetchOne($check, [$data[$idKey]], $errors);
            if ($check) {
                 dbUpdate($table, $data, [$idKey => $data[$idKey]], $errors);
            }
            // dd($data[$idKey],"---12--");
            return $data[$idKey];
        }
    }
    // dd($data,"---129--");
    // dd($data,"---12--");
    return dbInsert($table, $data, $errors);
}

/**
 * Paginate a SQL query
 * @param string $sql SQL query string
 * @param int $size Page size
 * @param array $params Query parameters
 * @param array &$errors Array to store any errors
 * @return array Paginated results
 */
function dbPaginate($sql, $size, $params = [], &$errors = [])
{
    // dd($sql,$size,$params);
    $page = $_REQUEST['page'] ?? 1;
    $offset = ($page - 1) * $size;
    $sql .= " LIMIT $size";
    $sql .= " OFFSET $offset";
    // dd($sql,$params);
    return dbFetchAll($sql, $params, $errors);
}

/**
 * Count total rows from a SQL query
 * @param string $sql SQL query string
 * @param array $params Query parameters
 * @param array &$errors Array to store any errors
 * @return int Total count
 */
function dbCount($sql, $params = [], &$errors = [])
{
    $sqlCount = "SELECT COUNT(*) FROM ($sql) AS c";
    return dbFetchColumn($sqlCount, $params, $errors);
}


/**
 * Sum a column from a SQL query
 * @param string $sql SQL query string
 * @param string $column Column name
 * @param array $params Query parameters
 * @param array &$errors Array to store any errors
 * @return float Total sum
 */
function dbSum($sql, $column, $params = [], &$errors = [])
{
    $sqlSum = "SELECT SUM($column) FROM ($sql) AS c";
    // dd($sqlSum,$params);
    return dbFetchColumn($sqlSum, $params, $errors);
}
