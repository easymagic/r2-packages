<?php 

namespace R2Packages\Framework\Ecommerce\Cart;

use R2Packages\Framework\Migration;

class CartMigration
{
    public function run()
    {
        Migration::table('carts')
            ->field('id')->definition("INT(11) AUTO_INCREMENT PRIMARY KEY")->run()
            ->field('cart_session_id')->definition("INT(11) DEFAULT NULL")->run()
            ->field('product_id')->definition("INT(11) DEFAULT NULL")->run()
            ->field('quantity')->definition("INT(11) DEFAULT 0")->run()
            ->field('created_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run()
            ->field('updated_at')->definition("TIMESTAMP DEFAULT CURRENT_TIMESTAMP")->run();
    }
}