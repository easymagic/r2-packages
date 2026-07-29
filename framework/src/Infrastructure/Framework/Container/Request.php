<?php 

namespace R2Packages\Framework\Infrastructure\Framework\Container;

class Request
{
    public $data = [];
    public $input = [];

    /**
     * @var Request|null
     */
    private static $instance = null;

    /**
     * Constructor
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->input = [];
    }

    public static function getInstance($request = []){
        if (!isset(self::$instance)) {
            self::$instance = new self($request);
        }
        return self::$instance;
    }

    function get(string $key){
        return $this->data[$key] ?? '';
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