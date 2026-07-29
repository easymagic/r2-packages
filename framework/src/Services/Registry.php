<?php 

namespace R2Packages\Framework\Services;

use Exception;

class Registry {

    private static $data = [];

    public  function set($key, $value) {
        self::$data[$key] = $value;
    }

    public  function get($key) {
        if (!isset(self::$data[$key])) {
            throw new Exception("Key not found in registry: [$key]");
        }
        return self::$data[$key];
    }
}