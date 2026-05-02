<?php 

namespace R2Packages\Framework;

use Closure;
use R2Packages\Framework\Entities\BaseUserEntity;

class Boostrap
{
    public static function run($callback = null){
        $container = Container::getInstance();
        
        $container->set(BaseUserEntity::class,function($data = []){
            return new BaseUserEntity($data);
        });


        if(!empty($callback) && $callback instanceof Closure){
            $callback($container);
        }
    }
}