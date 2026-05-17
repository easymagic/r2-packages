<?php 
namespace R2Packages\Framework;

class Response
{

    private static $instance = null;

    private $data = [];
    private $status = 200;

    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function write($data, $status = 200){
       foreach($data as $key => $value){
        $this->data[$key] = $value;
       }
       $this->status = $status;
       return $this;
    }


    public function json()
    {
        header('Content-Type: application/json');
        http_response_code($this->status);
        echo json_encode($this->data);
        exit;
    }
}