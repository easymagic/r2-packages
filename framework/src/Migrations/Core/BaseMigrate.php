<?php

namespace R2Packages\Framework\Migrations\Core;

use R2Packages\Framework\BaseUser\BaseUserMigration;
use R2Packages\Framework\Feature\FeatureMigration;
use R2Packages\Framework\FeatureSetting\FeatureSettingMigration;
use R2Packages\Framework\Settings\SettingsMigration;

class BaseMigrate {

    private BaseUserMigration $baseUserMigration;
    private SettingsMigration $settingsMigration;
    private FeatureMigration $featureMigration;
    private FeatureSettingMigration $featureSettingMigration;


    public function __construct(
        BaseUserMigration $baseUserMigration,
        SettingsMigration $settingsMigration,
        FeatureMigration $featureMigration,
        FeatureSettingMigration $featureSettingMigration
    ) {
        $this->baseUserMigration = $baseUserMigration;
        $this->settingsMigration = $settingsMigration;
        $this->featureMigration = $featureMigration;
        $this->featureSettingMigration = $featureSettingMigration;
    }

    public function run() {
        $this->baseUserMigration->run();
        $this->settingsMigration->run();
        $this->featureMigration->run();
        $this->featureSettingMigration->run();
        echo 'Core-Migrated';
    }
}