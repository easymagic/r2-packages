<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Container;

use Exception;
use R2Packages\Framework\Infrastructure\Framework\Container\ContainerServiceInterface;
use ReflectionClass;

class ContainerService implements ContainerServiceInterface
{
    private $services = [];
    private $mappedServices = [];
    private $mappedSingletonServices = [];

    public function get(string $service, array $args = []){

        // get concrete class
        if (isset($this->mappedSingletonServices[$service]) && is_object($this->mappedSingletonServices[$service])){
            return $this->mappedSingletonServices[$service];
        }

        if (isset($this->mappedSingletonServices[$service])){
            $this->mappedSingletonServices[$service] = $this->resolve($this->mappedSingletonServices[$service]);
            return $this->mappedSingletonServices[$service];
        }

        if (isset($this->mappedServices[$service])){
            $concreteClass = $this->resolve($this->mappedServices[$service]);
            return $concreteClass;
        }

        if(isset($this->services[$service])){
            if (is_callable($this->services[$service])){
                return $this->services[$service]($args);
            }

            if (is_object($this->services[$service])){
                return $this->services[$service];
            }
        }

        return $this->resolve($service, $args);
    }

    private function resolve(string $class, $data = [])
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
                    // continue;
                }

                continue;
                // throw new Exception("Cannot resolve parameter {$param->getName()} in {$class}");
            }

            $typeName = $type->getName();
            $dependencies[] = $this->get($typeName, $data);
            continue;

        }

        $dependencies[] = $data;

        return $reflection->newInstanceArgs($dependencies);
    }    

    /**
     * Set a service in the container
     * @param string $service
     * @param mixed $instance
     * @return $this
     */
    public function set(string $service, $instance){
        $this->services[$service] = $instance;
        return $this;
    }

    public function unset(string $service){
        unset($this->services[$service]);
        return $this;
    }

    public function map(string $interfaceClass, string $implementationClass){
        $this->mappedServices[$interfaceClass] = $implementationClass;
        return $this;
    }

    public function singleton(string $interfaceClass, string $implementationClass){
        $this->mappedSingletonServices[$interfaceClass] = $implementationClass;
       return $this;
    }
}