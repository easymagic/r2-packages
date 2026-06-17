<?php

namespace R2Packages\Framework\Ecommerce\Controllers;

use R2Packages\Framework\Ecommerce\Migrations\CategoryMigration;
use R2Packages\Framework\Ecommerce\Migrations\ProductMigration;
use R2Packages\Framework\Migrations\BaseUserMigration;

class EcommerceMigrationController
{
    private CategoryMigration $categoryMigration;
    private ProductMigration $productMigration;
    private BaseUserMigration $baseUserMigration;

    public function __construct(
        CategoryMigration $categoryMigration,
        ProductMigration $productMigration,
        BaseUserMigration $baseUserMigration
    ) {
        $this->categoryMigration = $categoryMigration;
        $this->productMigration = $productMigration;
        $this->baseUserMigration = $baseUserMigration;
    }
    public function migrate()
    {
        $this->baseUserMigration->run();
        $this->categoryMigration->run();
        $this->productMigration->run();
    }
}