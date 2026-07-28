<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Db;

use PDO;

interface DbConnectionServiceInterface
{
    /**
     * Get the database connection
     * @return PDO
     */
    public function getConnection();
}