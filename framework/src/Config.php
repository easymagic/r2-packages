<?php

use R2Packages\Framework\Utils;

function initConfig($dir)
{
    Utils::load_env(".env");

    define("DIR_PATH", $dir);

    define('APP_PATH', basename(DIR_PATH));
    
    define('ROOT_DIR', dirname(DIR_PATH) . '/' . APP_PATH);

    if (isLocal()) {
        define('MAIL_SERVICE', 'mailtrap'); // Supported: 'mailtrap' or 'mail'
    } else {
        define('MAIL_SERVICE', 'mail');
    }
    
    // -------------------------------------------------------------
    // Autoloader
    // -------------------------------------------------------------
    spl_autoload_register(function ($class) {
        $path = DIR_PATH . '/../src/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($path)) {
            include_once $path;
        }
    });
}

function mail_service(){
    return MAIL_SERVICE;
}

function routeListen(){
    include_once DIR_PATH . '/../src/routes/web.php';
    $route->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);    
}
