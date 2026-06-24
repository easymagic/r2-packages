<?php

namespace R2Packages\Framework\Migrations\Core;

use R2Packages\Framework\Migrations\BaseUserMigration;

class BaseMigrate {

    private BaseUserMigration $baseUserMigration;

    public function __construct(
        BaseUserMigration $baseUserMigration
    ) {
        $this->baseUserMigration = $baseUserMigration;
    }

    public function run() {
        $this->baseUserMigration->run();
        echo 'Base-Migrated';
    }
}