<?php 

namespace R2Packages\Framework\Migrations;

use R2Packages\Framework\Migration;

class SettingsMigration {

    public function run() {
        Migration::table('settings')
            ->field('setting_key')->definition("VARCHAR(255) DEFAULT ''")->run()
            ->field('setting_value')->definition("TEXT DEFAULT NULL")->run();
    }
}