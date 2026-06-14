<?php 

namespace R2Packages\Framework;

class Request
{
    public $data = [];
    public $input = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->input = [];
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

    /**
     * Get a field value
     * @param string $field
     * @return mixed
     */
    public function get($field){
        return $this->data[$field] ?? null;
    }
}