<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Router;

interface RouteServiceInterface
{
    public function get( string $path, mixed $callback);
    public function post( string $path, mixed $callback);
    public function delete( string $path, mixed $callback);
    public function put( string $path, mixed $callback);
    public function prefix( string $prefix, callable $callback);
    public function middleware( array $middleware, callable $callback);
    public function run( string $path, string $method);
}