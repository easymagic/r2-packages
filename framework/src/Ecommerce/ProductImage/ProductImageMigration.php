<?php 

namespace R2Packages\Framework\Ecommerce\ProductImage;

use R2Packages\Framework\Migration;

class ProductImageMigration {

    public function run() {
        Migration::table('product_images')
            ->field('product_id')->definition("INT(11) DEFAULT 0")->run()
            ->field('image_url')->definition("VARCHAR(500) DEFAULT ''")->run()
            ->field('alt_text')->definition("VARCHAR(255) DEFAULT NULL")->run()
            ->field('sort_order')->definition("INT(11) DEFAULT 0")->run()
            ->field('is_primary')->definition("TINYINT(1) DEFAULT 0")->run()
            ->field('is_active')->definition("TINYINT(1) DEFAULT 1")->run()
            ->field('created_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run();
    }
}
