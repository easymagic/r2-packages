<?php 

namespace R2Packages\Framework\Repositories\Ids;

use Exception;
use R2Packages\Framework\Entities\FeatureEntity;
use R2Packages\Framework\Repositories\FeatureRepository;
use R2Packages\Framework\Request;

class FeatureIdRepository {

    
    private Request $request;

    private FeatureRepository $featureRepository;

    public function __construct(Request $request, FeatureRepository $featureRepository)
    {
        $this->request = $request;
        $this->featureRepository = $featureRepository;
    }

    public function getFeature()
    {
        if ($this->request->isEmpty('feature_id')) {
            throw new Exception("Feature ID is required!");
        }
        $feature = $this->featureRepository->find($this->request->get('feature_id'));
        if ($feature->isEmpty()) {
            throw new Exception("Feature not found!");
        }
        return $feature;

    }
}