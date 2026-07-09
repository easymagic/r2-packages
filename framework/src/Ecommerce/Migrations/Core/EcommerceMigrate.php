<?php

namespace R2Packages\Framework\Ecommerce\Migrations\Core;

use R2Packages\Framework\Ecommerce\Category\CategoryMigration;
use R2Packages\Framework\Ecommerce\Migrations\BnplFeatureMigration;
use R2Packages\Framework\Ecommerce\Product\ProductMigration;
use R2Packages\Framework\Ecommerce\ProductImage\ProductImageMigration;
use R2Packages\Framework\BaseUser\BaseUserMigration;

class EcommerceMigrate {

    private CategoryMigration $categoryMigration;
    private ProductMigration $productMigration;
    private ProductImageMigration $productImageMigration;
    private BaseUserMigration $baseUserMigration;
    private BnplFeatureMigration $bnplFeatureMigration;

    public function __construct(
        CategoryMigration $categoryMigration,
        ProductMigration $productMigration,
        BaseUserMigration $baseUserMigration,
        BnplFeatureMigration $bnplFeatureMigration,
        ProductImageMigration $productImageMigration
    ) {
        $this->categoryMigration = $categoryMigration;
        $this->productMigration = $productMigration;
        $this->baseUserMigration = $baseUserMigration;
        $this->bnplFeatureMigration = $bnplFeatureMigration;
        $this->productImageMigration = $productImageMigration;
    }
    public function run() {
        $this->baseUserMigration->run();
        $this->categoryMigration->run();
        $this->productMigration->run();
        $this->bnplFeatureMigration->run();
        $this->productImageMigration->run();
        
        echo 'Ecommerce-Migrated';
    }
}