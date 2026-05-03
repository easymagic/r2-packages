<?php 

namespace R2Packages\Framework;

use Exception;

class Container
{
    private $services = [];

    private static $instance = null;
    private $singletons = [];
    private $instances = [];

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
        if (isset($this->singletons[$service]) && $this->singletons[$service]){
            if (isset($this->instances[$service]) && !empty($this->instances[$service])){
                return $this->instances[$service];
            }
            $this->instances[$service] = $this->services[$service]($args);
            return $this->instances[$service];
        }else{
            if(isset($this->services[$service])){
                return $this->services[$service]($args);
            }
            throw new Exception("Service not found!");    
        }
    }

    public function set($service, $instance){
        $this->services[$service] = $instance;
        return $this;
    }

    public function singleton($service, $instance){
        $this->set($service, $instance);
        $this->singletons[$service] = true;
        $this->instances[$service] = null;
        return $this;
    }


}