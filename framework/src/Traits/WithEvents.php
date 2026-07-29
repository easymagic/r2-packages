<?php 

namespace R2Packages\Framework\Traits;

trait WithEvents
{

    private static $listeners = [];

    public static function on($event, $callback)
    {
        self::$listeners[$event][] = $callback;
    
    }

    public static function dispatch($event,...$data)
    {
        if(isset(self::$listeners[$event])){
            foreach(self::$listeners[$event] as $listener){
                $listener(...$data);
            }
        }
    }
}