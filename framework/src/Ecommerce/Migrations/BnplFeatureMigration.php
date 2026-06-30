<?php

namespace R2Packages\Framework\Ecommerce\Migrations;

use R2Packages\Framework\Migration;
use R2Packages\Framework\Repositories\FeatureRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\FeatureService;
use R2Packages\Framework\Services\FeatureSettingService;

class BnplFeatureMigration
{

    private FeatureService $featureService;
    private Request $request;
    private FeatureSettingService $featureSettingService;
    private FeatureRepository $featureRepository;

    const BNPL_FEATURE_NAME = "BNPL - Buy Now, Pay Later";
    const BNPL_FEATURE_DESCRIPTION = "This feature allows customers to buy now and pay later.";
    const BNPL_FEATURE_IS_ACTIVE = 0;

    const INTERVAL_MONTHS = 'INTERVAL_MONTHS';
    const MINIMUM_AMOUNT = 'MINIMUM_AMOUNT';

    public function __construct(
        FeatureService $featureService,
        Request $request,
        FeatureSettingService $featureSettingService,
        FeatureRepository $featureRepository
    ) {
        $this->featureService = $featureService;
        $this->request = $request;
        $this->featureSettingService = $featureSettingService;
        $this->featureRepository = $featureRepository;
    }

    function isEnabled()
    {
        $feature = $this->featureRepository->findByName(self::BNPL_FEATURE_NAME);
        if($feature->isEmpty()){
            return false;
        }
        return $feature->isEnabled();
    }

    public function run()
    {
        $request = $this->request->newInstance([
            "name" => self::BNPL_FEATURE_NAME,
            "description" => self::BNPL_FEATURE_DESCRIPTION,
            "is_active" => self::BNPL_FEATURE_IS_ACTIVE,
        ]);
        $feature = $this->featureService->createFeature($request);

        $request = $this->request->newInstance([
            "setting_key" => self::INTERVAL_MONTHS,
            "setting_value" => 3,
        ]);
        $this->featureSettingService->addSetting($request, $feature);

        $request = $this->request->newInstance([
            "setting_key" => self::MINIMUM_AMOUNT,
            "setting_value" => 10000,
        ]);
        $this->featureSettingService->addSetting($request, $feature);
    }
}
