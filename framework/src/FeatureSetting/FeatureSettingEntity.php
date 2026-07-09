<?php 

namespace R2Packages\Framework\FeatureSetting;

class FeatureSettingEntity {

    public $id = 0;
    public $feature_id = 0;
    public $setting_key = '';
    public $setting_value = '';
    public $created_at = '';
    public $updated_at = '';

    public function __construct($data = [])
    {
        setAttributes($this, $data);
    }

    public function newInstance($data = [])
    {
        return new self($data);
    }

    public function isEmpty()
    {
        return empty($this->id);
    }

}