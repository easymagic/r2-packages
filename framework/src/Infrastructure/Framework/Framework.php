<?php

namespace R2Packages\Framework\Infrastructure\Framework;

use R2Packages\Framework\Infrastructure\Framework\Container\AppServiceContainer;
use R2Packages\Framework\Infrastructure\Framework\Container\ContainerService;
use R2Packages\Framework\Infrastructure\Framework\Cors\CorsService;
use R2Packages\Framework\Infrastructure\Framework\Dispatcher\DispatcherService;
use R2Packages\Framework\Infrastructure\Framework\Json\JsonResponseService;
use R2Packages\Framework\Infrastructure\Framework\Router\RouteService;

class Framework
{
    private string $dirPath = '';

    public function __construct(string $dirPath)
    {
        $this->dirPath = $dirPath;
    }

    /**
     * Boot the framework
     * @return AppServiceContainer
     */
    public function boot()
    {

        $corsService = new CorsService();
        $dispatcherService = new DispatcherService();
        $containerService = new ContainerService();
        $jsonResponseService = new JsonResponseService();
        $routeService = new RouteService(
            $dispatcherService,
            $containerService,
            $jsonResponseService
        );
        $appServiceContainer = new AppServiceContainer(
            $containerService,
            $routeService,
            $corsService
        );

        $appServiceContainer->allowCors();
        $appServiceContainer->boot(); // load services
        $appServiceContainer->registerAutoloader($this->dirPath . '/src');

        return $appServiceContainer;
    }
}
