<?php 

namespace R2Packages\Framework\Entities;

class SettingEntity {

    public $id = 0;
    public $setting_key = '';
    public $setting_value = '';

    public function __construct($data = [])
    {
        setAttributes($this, $data);
    }

    public function newInstance($data = [])
    {
        return new self($data);
    }

    public function isEmpty(){
        return empty($this->id);
    }
    
    
}