<?php 

namespace R2Packages\Framework;

use Closure;
use R2Packages\Framework\Entities\BaseUserEntity;
use R2Packages\Framework\Repositories\BaseUserRepository;
use R2Packages\Framework\Services\BaseUserService;

class Boostrap
{
    public static function run($callback = null){
        $container = Container::getInstance();
        
        $container->set(BaseUserEntity::class,function($data = []){
            return new BaseUserEntity($data);
        });

        $container->set(BaseUserRepository::class,function(){
            return new BaseUserRepository();
        });

        $container->set(BaseUserService::class,function(){
            return new BaseUserService();
        });

        if(!empty($callback) && $callback instanceof Closure){
            $callback($container);
        }
    }
}