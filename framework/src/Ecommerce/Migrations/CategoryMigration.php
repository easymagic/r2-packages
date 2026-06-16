<?php 

namespace R2Packages\Framework\Ecommerce\Migrations;

use R2Packages\Framework\Migration;

class CategoryMigration {

    public function run() {
        Migration::table('categories')
            ->field('parent_id')->definition("INT(11) DEFAULT NULL")->run()
            ->field('slug')->definition("VARCHAR(255) DEFAULT ''")->run()
            ->field('name')->definition("VARCHAR(255) DEFAULT ''")->run()
            ->field('description')->definition("TEXT DEFAULT NULL")->run()
            ->field('is_active')->definition("TINYINT(1) DEFAULT 1")->run()
            ->field('sort_order')->definition("INT(11) DEFAULT 0")->run()
            ->field('created_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run()
            ->field('updated_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run();
    }
}
