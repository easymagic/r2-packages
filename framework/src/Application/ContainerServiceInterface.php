<?php 
namespace R2Packages\Framework\Application;

interface ContainerServiceInterface
{
    public function get(string $service, array $args = []);
    public function set(string $service, object $instance);
    public function unset(string $service);
}