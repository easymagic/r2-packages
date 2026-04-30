<?php

use R2Packages\Framework\Utils;

function initConfig($dir)
{


    Utils::load_env(".env");

    define("DIR_PATH", $dir);

    define('SRC_DIR', DIR_PATH . '/../src');

    define('MIGRATIONS_DIR', SRC_DIR . '/migrations');

    define('APP_PATH', basename(DIR_PATH));
    // define('BASE_DIR', DIR_PATH . '/' . APP_PATH);
    define('ROOT_DIR', dirname(DIR_PATH) . '/' . APP_PATH);

    // define('SRC_DIR', DIR_PATH . '/../src');
    // define('INCLUDE_DIR', ROOT_DIR . '/src/includes');
    define('MAIL_TEMPLATE_DIR', SRC_DIR . '/mail_templates');


    if (isLocal()) {
        define('BASE_URL', $_ENV['BASE_URL_DEV']);
        define('MAIL_SERVICE', 'mailtrap'); // Supported: 'mailtrap' or 'mail'
    } else {
        define('BASE_URL', $_ENV['BASE_URL']);
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
