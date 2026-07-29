<?php

namespace R2Packages\Framework\Infrastructure\Framework\Db;

use PDO;
use R2Packages\Framework\Infrastructure\Framework\Db\DbConnectionServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Env\EnvServiceInterface;

class DbConnectionService implements DbConnectionServiceInterface
{

    private EnvServiceInterface $envService;

    /**
     * @var PDO
     */
    private static $pdo;

    public function __construct(EnvServiceInterface $envService)
    {
        $this->envService = $envService;
    }

    public function getConnection()
    {

        $dbHost = $this->envService->get('DB_HOST');
        $dbName = $this->envService->get('DB_NAME');
        $dbUser = $this->envService->get('DB_USER');
        $dbPassword = $this->envService->get('DB_PASSWORD');

        if (self::$pdo === null) {
            self::$pdo = new PDO("mysql:host=" . $dbHost . ";dbname=" . $dbName, $dbUser, $dbPassword);
        }
        // var_dump(self::$pdo);
        return self::$pdo;
    }
}
