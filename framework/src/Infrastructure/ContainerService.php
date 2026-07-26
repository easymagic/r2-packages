<?php 
namespace R2Packages\Framework\Infrastructure;

use Exception;
use R2Packages\Framework\Application\ContainerServiceInterface;
use ReflectionClass;

class ContainerService implements ContainerServiceInterface
{
    private $services = [];

    public function get(string $service, array $args = []){
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

            if (class_exists($typeName)) {
                $dependencies[] = $this->get($typeName, $data);
                continue;
            }

            if ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
                continue;
            }

            // throw new Exception("Cannot resolve dependency {$typeName} in {$class}");
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
}