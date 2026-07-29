<?php 

namespace R2Packages\Framework\Feature;

use R2Packages\Framework\FeatureSetting\FeatureSettingRepository;

class FeatureEntity {

    public $id = 0;
    public $name = '';
    public $description = '';
    public $is_active = 1;
    public $created_at = '';
    public $updated_at = '';

    public $feature_settings = [];

    public function __construct($featureSettings = [], $data = [])
    {
        setAttributes($this, $data);
        $this->feature_settings = $featureSettings;
    }

    public function newInstance($featureSettings = [], $data = [])
    {
        return new self($featureSettings, $data);
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