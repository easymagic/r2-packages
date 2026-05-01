<?php 

namespace R2Packages\Framework;

class Publisher
{
    public function publish()
    {
        copy(INTERNAL_PATH . '/migrations/UserMigration.php', ROOT_DIR . '/../src/migrations/UserMigration.php');
    }
}