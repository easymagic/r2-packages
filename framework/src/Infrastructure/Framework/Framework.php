<?php

namespace R2Packages\Framework\Infrastructure\Framework;

use R2Packages\Framework\Infrastructure\Framework\Container\AppServiceContainer;
use R2Packages\Framework\Infrastructure\Framework\Container\ContainerService;
use R2Packages\Framework\Infrastructure\Framework\Cors\CorsService;
use R2Packages\Framework\Infrastructure\Framework\Dispatcher\DispatcherService;
use R2Packages\Framework\Infrastructure\Framework\Env\EnvService;
use R2Packages\Framework\Infrastructure\Framework\Env\EnvServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Json\JsonResponseService;
use R2Packages\Framework\Infrastructure\Framework\Router\RouteService;

class Framework
{
    private string $dirPath = '';
    private AppServiceContainer $appServiceContainer;

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
        $envService = new EnvService();
        $routeService = new RouteService(
            $dispatcherService,
            $containerService,
            $jsonResponseService,
            $envService
        );
        $this->appServiceContainer = new AppServiceContainer(
            $containerService,
            $routeService,
            $corsService
        );

        $this->appServiceContainer->allowCors();
        $this->appServiceContainer->boot(); // load services
        $this->appServiceContainer->registerAutoloader($this->dirPath . '/');

        return $this->appServiceContainer;
    }

    /**
     * Get the environment service
     * @return EnvServiceInterface
     */
    public function getEnvService(){
        return $this->appServiceContainer->container()->get(EnvServiceInterface::class);
    }
}
