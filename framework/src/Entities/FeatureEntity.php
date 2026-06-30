<?php 

namespace R2Packages\Framework\Entities;

use R2Packages\Framework\Repositories\FeatureSettingRepository;

class FeatureEntity {

    private FeatureSettingRepository $featureSettingRepository;

    public $id = 0;
    public $name = '';
    public $description = '';
    public $is_active = 1;
    public $created_at = '';
    public $updated_at = '';

    public $feature_settings = [];

    public function __construct(FeatureSettingRepository $featureSettingRepository, $data = [])
    {
        setAttributes($this, $data);
        $this->feature_settings = $featureSettingRepository->filterByFeatureId($this->id)->fetchAll();
    }

    public function newInstance($data = [])
    {
        return new self($this->featureSettingRepository, $data);
    }

    public function isEmpty()
    {
        return empty($this->id);
    }

    function isEnabled()
    {
        return (int)$this->is_active === 1;
    }


}