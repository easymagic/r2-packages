<?php 

namespace R2Packages\Framework\Ecommerce\Migrations;

use R2Packages\Framework\Migration;

class ProductMigration {

    public function run() {
        Migration::table('products')
            ->field('category_id')->definition("INT(11) DEFAULT 0")->run()
            ->field('user_id')->definition("INT(11) DEFAULT 0")->run()
            ->field('sku')->definition("VARCHAR(100) DEFAULT ''")->run()
            ->field('slug')->definition("VARCHAR(255) DEFAULT ''")->run()
            ->field('name')->definition("VARCHAR(255) DEFAULT ''")->run()
            ->field('description')->definition("TEXT")->run()
            ->field('price')->definition("DECIMAL(12,2) DEFAULT 0.00")->run()
            ->field('compare_at_price')->definition("DECIMAL(12,2) DEFAULT NULL")->run()
            ->field('currency')->definition("VARCHAR(3) DEFAULT 'NGN'")->run()
            ->field('stock_qty')->definition("INT(11) DEFAULT 0")->run()
            ->field('is_active')->definition("TINYINT(1) DEFAULT 1")->run()
            ->field('created_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run()
            ->field('updated_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run();
    }
}
