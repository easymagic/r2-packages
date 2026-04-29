<?php

function jsonResponse($data, $status = 200){
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
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
