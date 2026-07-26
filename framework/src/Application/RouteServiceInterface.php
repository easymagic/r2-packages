<?php 
namespace R2Packages\Framework\Application;

interface RouteServiceInterface
{
    public function get( string $path, callable $callback);
    public function post( string $path, callable $callback);
    public function delete( string $path, callable $callback);
    public function put( string $path, callable $callback);
    public function prefix( string $prefix, callable $callback);
    public function middleware( array $middleware, callable $callback);
    public function run( string $path, string $method);
}