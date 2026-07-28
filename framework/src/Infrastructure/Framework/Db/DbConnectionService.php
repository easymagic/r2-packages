<?php

namespace R2Packages\Framework\Infrastructure\Framework\Db;

use PDO;
use R2Packages\Framework\Infrastructure\Framework\Db\DbConnectionServiceInterface;

class DbConnectionService implements DbConnectionServiceInterface
{
    /**
     * @var PDO
     */
    private static $pdo;

    public function getConnection()
    {

        // DB_HOST, DB_NAME, DB_USER, DB_PASSWORD
        if (!defined("DB_HOST")) {
            define("DB_HOST", 'localhost');
        }

        if (!defined("DB_NAME")) {
            define("DB_NAME", 'test');
        }

        if (!defined("DB_USER")) {
            define("DB_USER", 'root');
        }

        if (!defined("DB_PASSWORD")) {
            define("DB_PASSWORD", '');
        }

        if (self::$pdo === null) {
            self::$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        }
        // var_dump(self::$pdo);
        return self::$pdo;
    }
}
