<?php

namespace R2Packages\Framework\Services;

class CorrsService
{

    public function __construct() {}

    function allow()
    {
        // Browser requests from the Vite dev UI (e.g. localhost:8080) hit the API on another
        // port (e.g. localhost:2020) — different origins, so CORS is required even in Docker.
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        if ($origin !== '') {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, x-token, x-user-token, x-user-id, X-Requested-With');
            header('Vary: Origin');
        }

        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }
}
