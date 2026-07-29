<?php

namespace R2Packages\Framework\Migrations;

use R2Packages\Framework\Migrations\Core\BaseMigrate;

class MigrationController
{
    private BaseMigrate $baseMigrate;

    public function __construct(BaseMigrate $baseMigrate)
    {
        $this->baseMigrate = $baseMigrate;
    }

    public function migrate()
    {
        $this->baseMigrate->run();
    }
}
