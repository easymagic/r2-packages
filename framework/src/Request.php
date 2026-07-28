<?php 

namespace R2Packages\Framework;

use Exception;

class Request
{
    public $data = [];
    public $input = [];

    /**
     * Constructor
     * @param array $data
     */
    public function __construct($data)
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
        return $this->data[$field] ?? '';
    }

    public function require(string $field, string $message = ''): self{
        if ($this->isEmpty($field)) {
            throw new Exception($message ?? "The $field field is required");
        }
        $this->input[$field] = $this->data[$field];
        return $this;
    }

    public function optional(string $field, mixed $default = ''): self{
        if ($this->isEmpty($field)) {
            $this->input[$field] = $default;
            return $this;
        }
        $this->input[$field] = $this->data[$field];
        return $this;
    }
}