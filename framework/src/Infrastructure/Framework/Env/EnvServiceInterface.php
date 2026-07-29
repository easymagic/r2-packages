<?php

namespace R2Packages\Framework\Infrastructure\Framework\Env;

interface EnvServiceInterface
{
    /**
     * Get the value of the environment variable
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Load the environment variables from the file
     * @param string $path
     * @return void
     */
    public function loadEnv(string $path);
}