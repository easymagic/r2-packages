<?php

namespace R2Packages\Framework;

class Dispatcher {
    
    private $params = [];



    function match($url,$path){

        // dd($url, $path);

        $urlParts = explode("/",$url);
        $pathParts = explode("/",$path);
        
        if (count($urlParts) !== count($pathParts)) {
            return false;
        }
        
        foreach($urlParts as $key => $value){
            if($this->isParam($pathParts[$key])){
                $this->params[$this->getParamName($pathParts[$key])] = $value;
                continue;
            }
            if($value !== $pathParts[$key]){
                $this->params = []; // Reset params if match fails
                return false;
            }
        }

        return true;

    }


    function isParam($value){
        return strpos($value, "{") !== false && strpos($value, "}") !== false;
    }

    function getParamName($value){
        return str_replace(["{","}"], "", $value);
    }

    function getParams(){
        return $this->params;
    }

    

}