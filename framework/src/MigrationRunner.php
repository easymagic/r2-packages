<?php

namespace R2Packages\Framework;

class Migration
{

    public static function run($migrationsDir){
        $kernel = include $migrationsDir . '/kernel.php';
        foreach($kernel as $migration){
            $migration = new $migration();
            $migration->run();
        }
    }

}