<?php 

namespace R2Packages\Framework\Services\ids;

use Exception;
use R2Packages\Framework\Repositories\FeatureSettingRepository;
use R2Packages\Framework\Request;

class FeatureSettingIdService {
    private Request $request;
    private FeatureSettingRepository $featureSettingRepository;

    public function __construct(Request $request, FeatureSettingRepository $featureSettingRepository)
    {
        $this->request = $request;
        $this->featureSettingRepository = $featureSettingRepository;
    }

    public function getFeatureSetting()
    {
        if ($this->request->isEmpty('feature_setting_id')) {
            throw new Exception("Feature setting ID is required!");
        }
        $featureSetting = $this->featureSettingRepository->find($this->request->get('feature_setting_id'));
        if ($featureSetting->isEmpty()) {
            throw new Exception("Feature setting not found!");
        }
        return $featureSetting;
    }
}