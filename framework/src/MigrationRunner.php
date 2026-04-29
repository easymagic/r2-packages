<?php

namespace R2Packages\Framework;

class MigrationRunner
{

    public static function run($migrationsDir){
        $kernel = include $migrationsDir . '/kernel.php';
        foreach($kernel as $migration){
            $migration = new $migration();
            $migration->run();
        }
    }

}