<?php

namespace R2Packages\Framework\Infrastructure\Framework\Container;

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
        $this->container->set(ContainerServiceInterface::class, function () {
            return new ContainerService();
        });

        $this->container->set(DispatcherServiceInterface::class, function () {
            return new DispatcherService();
        });

        $this->container->set(RouteServiceInterface::class, function () {
            return new RouteService(
                $this->container->get(DispatcherServiceInterface::class),
                $this->container->get(ContainerServiceInterface::class),
                $this->container->get(JsonResponseServiceInterface::class)
            );
        });

        $this->container->set(DbServiceInterface::class, function () {
            return new DbService(
                $this->container->get(DbConnectionServiceInterface::class)
            );
        });

        $this->container->set(CorsServiceInterface::class, function () {
            return new CorsService();
        });

        $this->container->set(ValidationServiceInterface::class, function () {
            return new ValidationService();
        });

        $this->container->set(JsonResponseServiceInterface::class, function () {
            return new JsonResponseService();
        });

        $this->container->set(DbConnectionServiceInterface::class, function () {
            return new DbConnectionService(
                $this->container->get(EnvServiceInterface::class)
            );
        });

        $this->container->set(Migration::class, function () {
            return new Migration(
                $this->container->get(DbServiceInterface::class)
            );
        });

        $this->container->set(MailServiceInterface::class, function () {
            return new MailService(
                $this->container->get(EnvServiceInterface::class)
            );
        });

        $this->container->set(FileUploadServiceInterface::class, function () {
            return new FileUploadService();
        });

        $this->container->set(EnvServiceInterface::class, function () {
            return EnvService::getInstance();
        });
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
