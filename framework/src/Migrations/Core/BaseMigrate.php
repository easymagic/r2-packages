<?php

namespace R2Packages\Framework\Migrations\Core;

use R2Packages\Framework\Migrations\BaseUserMigration;
use R2Packages\Framework\Migrations\SettingsMigration;

class BaseMigrate {

    private BaseUserMigration $baseUserMigration;
    private SettingsMigration $settingsMigration;

    public function __construct(
        BaseUserMigration $baseUserMigration,
        SettingsMigration $settingsMigration
    ) {
        $this->baseUserMigration = $baseUserMigration;
        $this->settingsMigration = $settingsMigration;
    }

    public function run() {
        $this->baseUserMigration->run();
        $this->settingsMigration->run();
        echo 'Core-Migrated';
    }
}