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

    public static function load_env($path)
    {
        if (!file_exists($path)) return;
    
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
    
            list($key, $value) = explode('=', $line, 2);
    
            putenv(trim($key) . '=' . trim($value));
            $_ENV[trim($key)] = trim($value);
        }
    }
    
    
}