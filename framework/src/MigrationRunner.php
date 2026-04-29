<?php

namespace R2Packages\Framework;

class MigrationRunner
{

    public static function run(){
        $kernel = include MIGRATIONS_DIR . '/kernel.php';
        foreach($kernel as $migration){
            $migration = new $migration();
            $migration->run();
        }
    }

}