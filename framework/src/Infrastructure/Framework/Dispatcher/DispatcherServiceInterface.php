<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Dispatcher;

interface DispatcherServiceInterface
{
    public function match(string $url, string $path);
    public function isParam(string $value);
    public function getParamName(string $value);
    public function getParams();
}