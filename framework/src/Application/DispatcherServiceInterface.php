<?php 
namespace R2Packages\Framework\Application;

interface DispatcherServiceInterface
{
    public function match(string $url, string $path);
    public function isParam(string $value);
    public function getParamName(string $value);
    public function getParams();
}