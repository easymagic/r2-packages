<?php 

namespace R2Packages\Framework\Ecommerce\Controllers;

use Exception;
use R2Packages\Framework\Repositories\FeatureRepository;
use R2Packages\Framework\Request;
use R2Packages\Framework\Services\FeatureService;
use R2Packages\Framework\Services\FeatureSettingService;
use R2Packages\Framework\Services\ids\FeatureIdService;
use R2Packages\Framework\Services\ids\FeatureSettingIdService;

class FeatureController {
    private FeatureService $featureService;
    private FeatureSettingService $featureSettingService;
    private Request $request;
    private FeatureRepository $featureRepository;
    private FeatureIdService $featureIdService;
    private FeatureSettingIdService $featureSettingIdService;



    public function __construct(
        FeatureService $featureService,
        FeatureSettingService $featureSettingService,
        Request $request,
        FeatureRepository $featureRepository,
        FeatureIdService $featureIdService,
        FeatureSettingIdService $featureSettingIdService
    ) {
        $this->featureService = $featureService;
        $this->featureSettingService = $featureSettingService;
        $this->request = $request;
        $this->featureRepository = $featureRepository;
        $this->featureIdService = $featureIdService;
        $this->featureSettingIdService = $featureSettingIdService;
    }

    public function index()
    {
        $features = $this->featureRepository->fetchAll();
        jsonResponse([
            'message' => 'Features fetched successfully',
            'data' => $features,
            "success" => true
        ]);
    }

    public function enableFeature()
    {
        $feature = $this->featureIdService->getFeature();
        $this->featureService->enableFeature($feature);
        jsonResponse([
            'message' => 'Feature enabled successfully',
            'data' => $feature,
            "success" => true
        ]);
    }

    public function disableFeature()
    {
        $feature = $this->featureIdService->getFeature();
        $this->featureService->disableFeature($feature);
        jsonResponse([
            'message' => 'Feature disabled successfully',
            'data' => $feature,
            "success" => true
        ]);
    }

    public function getFeatureSettings(){
        $feature = $this->featureIdService->getFeature();
        $settings = $feature->feature_settings;
        jsonResponse([
            'message' => 'Feature settings fetched successfully',
            'data' => $settings,
            "success" => true
        ]);
    }

    public function updateFeatureSetting(){
        $feature = $this->featureIdService->getFeature();
        $setting = $this->featureSettingIdService->getFeatureSetting();

        if ($setting->feature_id !== $feature->id) {
            throw new Exception("Feature setting not found!");
        }

        $this->featureSettingService->updateSetting($this->request, $setting);
        jsonResponse([
            'message' => 'Feature setting updated successfully',
            'data' => $setting,
            "success" => true
        ]);
    }


}