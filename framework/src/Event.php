<?php 

namespace R2Packages\Framework;

class Event
{

    private $listeners = [];
    private static $instance = null;

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function on($event, $callback){
        $this->listeners[$event][] = $callback;
        return $this;
    }

    public function dispatch($event, $data = []){
        if(isset($this->listeners[$event])){
            foreach($this->listeners[$event] as $listener){
                $listener($data);
            }
        }
        return $this;
    }
}