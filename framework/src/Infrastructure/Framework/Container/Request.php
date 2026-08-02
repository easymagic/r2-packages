<?php 

namespace R2Packages\Framework\Infrastructure\Framework\Container;

class Request
{
    public $data = [];
    public $input = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = $_REQUEST;
        $this->input = [];
    }

    function get(string $key,mixed $default = ''){
        return $this->data[$key] ?? $default;
    }

    public function all()
    {
        return $this->data;
    }

    function newInstance($data = [])
    {
        return new self($data);
    }

    /**
     * Check if a field is empty
     * @param string $field
     * @return bool
     */
    public function isEmpty($field){
        return !isset($this->data[$field]) || empty($this->data[$field]);
    }

}