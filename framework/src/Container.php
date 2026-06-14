<?php 

namespace R2Packages\Framework;

use Exception;
use ReflectionClass;

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
            if (is_callable($this->services[$service])){
                return $this->services[$service]($args);
            }

            if (is_object($this->services[$service])){
                return $this->services[$service];
            }
        }

        return $this->resolve($service, $args);
        // if(class_exists($service)){
        //     return new $service($args);
        // }
        // throw new Exception("Service not found!");    
    }

    private function resolve(string $class, $request = [])
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $class();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            if (!$type) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                    continue;
                }

                throw new Exception("Cannot resolve parameter {$param->getName()} in {$class}");
            }

            $typeName = $type->getName();

            // if ($typeName === Request::class) {
            //     $dependencies[] = new Request($request);
            //     continue;
            // }

            if (class_exists($typeName)) {
                $dependencies[] = $this->get($typeName, $request);
                continue;
            }

            if ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
                continue;
            }

            throw new Exception("Cannot resolve dependency {$typeName} in {$class}");
        }

        $dependencies[] = $request;

        return $reflection->newInstanceArgs($dependencies);
    }    

    public function set($service, $instance){
        $this->services[$service] = $instance;
        return $this;
    }

    public function unset($service){
        unset($this->services[$service]);
        return $this;
    }

}