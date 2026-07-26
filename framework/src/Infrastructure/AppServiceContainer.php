<?php

namespace R2Packages\Framework\Infrastructure;

use R2Packages\Framework\Application\ContainerServiceInterface;
use R2Packages\Framework\Application\DispatcherServiceInterface;
use R2Packages\Framework\Application\RouteServiceInterface;

class AppServiceContainer
{
    private ContainerServiceInterface $container;
    private RouteServiceInterface $routeService;

    public function __construct(ContainerServiceInterface $containerService, RouteServiceInterface $routeService)
    {
        $this->container = $containerService;
        $this->routeService = $routeService;
    }

    function boot()
    {

        $this->container->set(ContainerServiceInterface::class, function () {
            return new ContainerService();
        });

        $this->container->set(DispatcherServiceInterface::class, function () {
            return new DispatcherService();
        });

        $this->container->set(RouteServiceInterface::class, function () {
            return new RouteService(
                $this->container->get(DispatcherServiceInterface::class),
                $this->container->get(ContainerServiceInterface::class)
            );
        });
    }

    function loadRoutes(callable $callback){
        $callback($this->routeService);
    }

    function run(string $path, string $method){
        $this->routeService->run($path, $method);
    }
}
