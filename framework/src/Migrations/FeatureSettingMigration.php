<?php 

namespace R2Packages\Framework\Migrations;

use R2Packages\Framework\Migration;

class FeatureSettingMigration {

    public function run() {
        Migration::table('feature_settings')
            ->field('feature_id')->definition("INT(11) DEFAULT 0")->run()
            ->field('setting_key')->definition("VARCHAR(255) DEFAULT ''")->run()
            ->field('setting_value')->definition("TEXT DEFAULT NULL")->run()
            ->field('created_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run()
            ->field('updated_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run();
    }
}