<?php 
namespace R2Packages\Framework\Application;

use PDO;

interface DbConnectionServiceInterface
{
    /**
     * Get the database connection
     * @return PDO
     */
    public function getConnection();
}