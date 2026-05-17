<?php

use R2Packages\Framework\Response;

function jsonResponse($data, $status = 200){
    Response::getInstance()->write($data, $status)->json();
}

function json($data, $status = 200){
    Response::getInstance()->write($data, $status)->json();
}

function setAttributes($object, $attributes){
    // var_dump($object);
    foreach ($attributes as $attribute => $value) {
        $object->$attribute = $value;
    }
    return $object;
}

function mail_template($templatePath, $data){
   ob_start();
   extract($data);
   include $templatePath;
   $content = ob_get_clean();
   return $content;
}

function route($name, $params = []){
    global $route;
    $path = $route->getRouteByName($name,$params);
    return $path;
}

function isLocal(){
    $isLocal =
    (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost') ||
    (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') ||
    (isset($_SERVER['SERVER_ADDR']) && 
        ($_SERVER['SERVER_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_ADDR'] === '::1')) ||
    (php_sapi_name() === 'cli-server');
    return $isLocal;
}