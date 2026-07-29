<?php

namespace R2Packages\Framework\FeatureSetting;

use R2Packages\Framework\FeatureSetting\FeatureSettingEntity;
use R2Packages\Framework\PaginationMetta;
use R2Packages\Framework\Ports\AbstractRepositoryPort;
use R2Packages\Framework\Repositories\DbRepository;
use R2Packages\Framework\Request;

class FeatureSettingRepository extends AbstractRepositoryPort
{

    private FeatureSettingEntity $featureSettingEntity;
    protected $table = 'feature_settings';
    protected $sql = 'SELECT * FROM feature_settings WHERE 1=1';

    public function __construct(
        FeatureSettingEntity $featureSettingEntity,
        DbRepository $dbRepository,
        PaginationMetta $paginationMeta,
        Request $request
    ) {
        parent::__construct($dbRepository, $paginationMeta, $request);

        $this->featureSettingEntity = $featureSettingEntity;
    }

    protected function applyCommonFilters()
    {
        if (!$this->request->isEmpty('feature_id')) {
          $this->filterByFeatureId($this->request->get('feature_id'));
        }
    }

    function filterByFeatureId($featureId)
    {
        $this->sql .= " AND feature_id = ?";
        $this->params[] = $featureId;
        return $this;
    }

    function filterBySettingKey($settingKey)
    {
        $this->sql .= " AND setting_key = ?";
        $this->params[] = $settingKey;
        return $this;
    }


    protected function hydrate($data)
    {
        $featureSetting = $this->featureSettingEntity->newInstance($data);
        return $featureSetting;
    }

}
