<?php 

namespace R2Packages\Framework\Services;

use Exception;
use R2Packages\Framework\Entities\FeatureEntity;
use R2Packages\Framework\Repositories\FeatureRepository;
use R2Packages\Framework\Repositories\FeatureSettingRepository;
use R2Packages\Framework\Request;

class FeatureService {
    
    private FeatureRepository $featureRepository;
    private FeatureSettingRepository $featureSettingRepository;

    public function __construct(FeatureRepository $featureRepository, FeatureSettingRepository $featureSettingRepository)
    {
        $this->featureRepository = $featureRepository;
        $this->featureSettingRepository = $featureSettingRepository;
    }

    public function createFeature(Request $request)
    {
        if($request->isEmpty('name')){
            throw new Exception("Name is required!");
        }
        $request->input['name'] = $request->get('name');
        $feature = $this->featureRepository->findByName($request->get('name'));
        if(!$feature->isEmpty()){
            return $this->updateFeature($request, $feature);
        }

        if($request->isEmpty('description')){
            throw new Exception("Description is required!");
        }
        $request->input['description'] = $request->get('description');

        $request->input['is_active'] = 0;

        $input = $request->input;
        return $this->featureRepository->save(0, $input);
    }

    public function updateFeature(Request $request, FeatureEntity $feature)
    {
        if($request->isEmpty('name')){
            throw new Exception("Name is required!");
        }
        $request->input['name'] = $request->get('name');

        if($request->isEmpty('description')){
            throw new Exception("Description is required!");
        }
        $request->input['description'] = $request->get('description');

        if(!$request->isEmpty('is_active')){
            $request->input['is_active'] = 1;
        }else{
            $request->input['is_active'] = 0;
        }

        return $this->featureRepository->save($feature->id, $request->input);
    }

    public function deleteFeature(FeatureEntity $feature)
    {
        $settings = $feature->feature_settings;
        /** @var FeatureSettingEntity $setting */
        foreach ($settings as $setting) {
            $this->featureSettingRepository->delete($setting->id);
        }
        return $this->featureRepository->delete($feature->id);
    }
}