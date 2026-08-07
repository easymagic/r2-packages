<?php

namespace R2Packages\Framework\Infrastructure\Framework\Container;

use R2Packages\Framework\Infrastructure\Framework\Db\QueryBuilderService;
use R2Packages\Framework\Infrastructure\Framework\Db\QueryBuilderServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Container\ContainerServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Cors\CorsService;
use R2Packages\Framework\Infrastructure\Framework\Cors\CorsServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Db\DbConnectionService;
use R2Packages\Framework\Infrastructure\Framework\Db\DbConnectionServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Db\DbService;
use R2Packages\Framework\Infrastructure\Framework\Db\DbServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Db\Migration;
use R2Packages\Framework\Infrastructure\Framework\Dispatcher\DispatcherService;
use R2Packages\Framework\Infrastructure\Framework\Dispatcher\DispatcherServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Json\JsonResponseServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Mail\MailService;
use R2Packages\Framework\Application\Mail\MailServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Env\EnvService;
use R2Packages\Framework\Infrastructure\Framework\Env\EnvServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\File\FileUploadService;
use R2Packages\Framework\Infrastructure\Framework\File\FileUploadServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Router\RouteService;
use R2Packages\Framework\Infrastructure\Framework\Router\RouteServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Validation\ValidationService;
use R2Packages\Framework\Infrastructure\Framework\Validation\ValidationServiceInterface;
use R2Packages\Framework\Infrastructure\Framework\Json\JsonResponseService;
use R2Packages\Framework\Infrastructure\Framework\Payment\PaymentService;
use R2Packages\Framework\Infrastructure\Framework\Payment\PaymentServiceInterface;

class AppServiceContainer
{
    private ContainerServiceInterface $container;
    private RouteServiceInterface $routeService;
    private CorsServiceInterface $corsService;

    public function __construct(
        ContainerServiceInterface $containerService,
        RouteServiceInterface $routeService,
        CorsServiceInterface $corsService
    ) {
        $this->container = $containerService;
        $this->routeService = $routeService;
        $this->corsService = $corsService;
    }

    function boot()
    {
        $this->container->singleton(ContainerServiceInterface::class, ContainerService::class);

        $this->container->map(DispatcherServiceInterface::class, DispatcherService::class);

        $this->container->map(RouteServiceInterface::class, RouteService::class);

        $this->container->singleton(DbServiceInterface::class, DbService::class);

        $this->container->map(CorsServiceInterface::class, CorsService::class);

        $this->container->map(ValidationServiceInterface::class, ValidationService::class);

        $this->container->map(JsonResponseServiceInterface::class, JsonResponseService::class);

        $this->container->singleton(DbConnectionServiceInterface::class, DbConnectionService::class);

        $this->container->singleton(MailServiceInterface::class, MailService::class);

        $this->container->map(FileUploadServiceInterface::class, FileUploadService::class);

        $this->container->singleton(EnvServiceInterface::class, EnvService::class);

        $this->container->singleton(PaymentServiceInterface::class, PaymentService::class);

        $this->container->map(QueryBuilderServiceInterface::class, QueryBuilderService::class);
    }

    function loadRoutes(callable $callback)
    {
        $callback($this->routeService);
    }

    function run(string $path, string $method)
    {
        $this->routeService->run($path, $method);
    }

    function registerAutoloader(string $dirPath)
    {
        // -------------------------------------------------------------
        // Autoloader
        // -------------------------------------------------------------
        spl_autoload_register(function ($class) use ($dirPath) {
            $path = $dirPath . '/' . str_replace('\\', '/', $class) . '.php';
            if (file_exists($path)) {
                include_once $path;
            }
        });
    }

    function executeCommand(string $command, array $params)
    {
        $this->routeService->executeCommand($command, $params);
    }


    function allowCors() {
        $this->corsService->allow();
    }

    /**
     * Get the container instance
     * @return ContainerServiceInterface
     */
    function container(){
        return $this->container;
    }
}
