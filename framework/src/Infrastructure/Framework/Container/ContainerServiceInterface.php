<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Container;

interface ContainerServiceInterface
{
    public function get(string $service, array $args = []);
    public function set(string $service, object $instance);
    public function unset(string $service);
    public function map(string $interfaceClass, string $implementationClass);
    public function singleton(string $interfaceClass, string $implementationClass);

}