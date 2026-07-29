<?php 

namespace R2Packages\Framework;

use PDO;

class Connection {

    
    private static $pdo = null;

    public static function getConnection($dbConfig) {
        if (self::$pdo === null) {
            self::$pdo = new PDO("mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['dbname'], $dbConfig['user'], $dbConfig['password']);
        }
        // var_dump(self::$pdo);
        return self::$pdo;
    }




}