<?php 

namespace R2Packages\Framework\Services;

use R2Packages\Framework\Repositories\SettingsRepository;

class MigrationDeployService {

    private SettingsRepository $settingsRepository;

    const ENABLE_RUN_MIGRATION = "ENABLE_RUN_MIGRATION";

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function runCheckApproval()
    {
        try {
            $settings = $this->settingsRepository->findByKey(self::ENABLE_RUN_MIGRATION);
            if ($settings->isEmpty()){
                return true; // do nothing since it's the first time and accessing settings will throw an exception
            }
            if ((int)$settings->setting_value === 1){
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return true; // do nothing since it's the first time and accessing settings will throw an exception
        }
    }
}