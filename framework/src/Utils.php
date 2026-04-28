<?php 
namespace R2Packages\Framework;

class Utils
{
    public static function dd($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();
    }


    public static function camelCaseToSnakeCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    public static function jsonResponse($data, $status = 200){
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
    
}