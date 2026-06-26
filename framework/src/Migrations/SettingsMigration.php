<?php 

namespace R2Packages\Framework\Migrations;

use R2Packages\Framework\Migration;

class SettingsMigration {

    public function run() {
        Migration::table('settings')
            ->field('key')->definition("VARCHAR(255) DEFAULT ''")->run()
            ->field('value')->definition("TEXT DEFAULT NULL")->run();
    }
}