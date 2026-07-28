<?php 
namespace R2Packages\Framework\Infrastructure\Framework\Json;


class JsonResponseService implements JsonResponseServiceInterface
{
    public function success(array $data = [])
    {
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

    public function error(string $message, int $status = 500)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
    }
}