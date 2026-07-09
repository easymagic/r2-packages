<?php

namespace R2Packages\Framework\Ecommerce\Migrations;

use R2Packages\Framework\Ecommerce\Migrations\Core\EcommerceMigrate;

class EcommerceMigrationController
{
    private EcommerceMigrate $ecommerceMigrate;

    public function __construct(EcommerceMigrate $ecommerceMigrate)
    {
        $this->ecommerceMigrate = $ecommerceMigrate;
    }

    public function migrate()
    {
        $this->ecommerceMigrate->run();
    }
}
