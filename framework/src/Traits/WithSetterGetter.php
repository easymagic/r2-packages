<?php 

namespace R2Packages\Framework\Traits;

trait WithSetterGetter
{

    public $data = [];

    function __get($name){
        return $this->data[$name] ?? null;
    }

    function __set($name, $value){
        $this->data[$name] = $value;
    }

    function __isset($name){
        return isset($this->data[$name]);
    }

    function __unset($name){
        unset($this->data[$name]);
    }

    public function init($data = [])
    {
        setAttributes($this, $data);
    }


}