<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Dispatcher;


class DispatcherService implements DispatcherServiceInterface
{
    private $params = [];

    public function match(string $url, string $path){

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


    public function isParam(string $value){
        return strpos($value, "{") !== false && strpos($value, "}") !== false;
    }

    public function getParamName(string $value){
        return str_replace(["{","}"], "", $value);
    }

    public function getParams(){
        return $this->params;
    }

}