<?php 

namespace R2Packages\Framework\Migrations;

use R2Packages\Framework\Migration;

class FeatureMigration {

    public function run() {
        Migration::table('features')
            ->field('name')->definition("VARCHAR(255) DEFAULT ''")->run()
            ->field('description')->definition("TEXT DEFAULT NULL")->run()
            ->field('is_active')->definition("TINYINT(1) DEFAULT 1")->run()
            ->field('created_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run()
            ->field('updated_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run();
    }
}