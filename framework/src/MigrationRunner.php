<?php

namespace R2Packages\Framework;

use R2Packages\Framework\Migrations\UserMigration;

// use App\Migrations\UserMigration;

class MigrationRunner
{

    public static function run($migrations = []){
        $migrations[] = UserMigration::class;
        // $kernel = include MIGRATIONS_DIR . '/kernel.php';
        foreach($migrations as $migration){
            $migration = new $migration();
            $migration->run();
        }
    }

}