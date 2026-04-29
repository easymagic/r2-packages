<?php


function initConfig($dir)
{
    define("DIR_PATH", $dir);

    define('SRC_DIR', DIR_PATH . '/../src');

    define('MIGRATIONS_DIR', SRC_DIR . '/migrations');

    define('APP_PATH', basename(DIR_PATH));
    // define('BASE_DIR', DIR_PATH . '/' . APP_PATH);
    define('ROOT_DIR', dirname(DIR_PATH) . '/' . APP_PATH);

    // define('SRC_DIR', DIR_PATH . '/../src');
    // define('INCLUDE_DIR', ROOT_DIR . '/src/includes');
    define('MAIL_TEMPLATE_DIR', SRC_DIR . '/mail_templates');



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
