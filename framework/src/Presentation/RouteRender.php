<?php 
namespace R2Packages\Framework\Presentation;

use R2Packages\Framework\Application\RouteServiceInterface;

class RouteRender
{
    private RouteServiceInterface $routeService;

    public function __construct(RouteServiceInterface $routeService)
    {
        $this->routeService = $routeService;
    }

    public function render(callable $callback)
    {
        $callback($this->routeService);
    }
}