<?php 

namespace R2Packages\Framework;

class Request
{
    public $data = [];
    public $input = [];

    public function __construct($data = [])
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
}