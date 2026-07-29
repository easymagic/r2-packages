<?php

namespace R2Packages\Framework\Infrastructure\Framework\Env;

class EnvService implements EnvServiceInterface
{

    private array $env = [];

    /**
     * @var EnvService|null
     */
    private static $instance = null;

    public static function getInstance(){
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function loadEnv(string $path){
        if (!file_exists($path)) return;
    
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
            $this->env[trim($key)] = trim($value);
        }

    }


    public function get(string $key)
    {
        return  isset($this->env[$key]) ? $this->env[$key] : '';
    }
}