<?php 

namespace R2Packages\Framework;

use Exception;

class Container
{
    private $services = [];

    private static $instance = null;

    /**
     * Get the instance of the container
     * @return Container
     */
    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($service, $args = []){
        if(isset($this->services[$service])){
            return $this->services[$service]($args);
        }
        if(class_exists($service)){
            return new $service($args);
        }
        throw new Exception("Service not found!");    
    }

    public function set($service, $instance){
        $this->services[$service] = $instance;
        return $this;
    }

}