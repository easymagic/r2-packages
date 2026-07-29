<?php 

namespace R2Packages\Framework\FeatureSetting;

use Exception;
use R2Packages\Framework\Feature\FeatureEntity;
use R2Packages\Framework\FeatureSetting\FeatureSettingEntity;
use R2Packages\Framework\FeatureSetting\FeatureSettingRepository;
use R2Packages\Framework\Request;

class FeatureSettingService {
    
    private FeatureSettingRepository $featureSettingRepository;


    public function __construct(FeatureSettingRepository $featureSettingRepository)
    {
        $this->featureSettingRepository = $featureSettingRepository;
    }

    public function addSetting(Request $request,FeatureEntity $feature)
    {
        if($request->isEmpty('setting_key')){
            throw new Exception("Setting key is required!");
        }
        $request->input['setting_key'] = $request->get('setting_key');

        $setting = $this->featureSettingRepository->filterBySettingKey($request->get('setting_key'))->fetchOne();
        if(!$setting->isEmpty()){
            return $setting;
        }

        $request->input['setting_value'] = $request->get('setting_value');
        $request->input['feature_id'] = $feature->id;
        return $this->featureSettingRepository->save(0, $request->input);
    }

    public function updateSetting(Request $request,FeatureSettingEntity $setting)
    {
        // if($request->isEmpty('setting_key')){
        //     throw new Exception("Setting key is required!");
        // }
        // $request->input['setting_key'] = $request->get('setting_key');
        $request->input['setting_value'] = $request->get('setting_value');
        return $this->featureSettingRepository->save($setting->id, $request->input);
    }

    public function deleteSetting(FeatureSettingEntity $setting)
    {
        return $this->featureSettingRepository->delete($setting->id);
    }
}