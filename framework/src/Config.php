<?php 


function initConfig($dir){
    define("DIR_PATH", $dir);

    define('SRC_DIR', DIR_PATH . '/../src');
    
    define('MIGRATIONS_DIR', SRC_DIR . '/migrations');    
}




