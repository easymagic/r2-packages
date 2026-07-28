<?php

namespace R2Packages\Framework\Infrastructure\Framework\Json;

interface JsonResponseServiceInterface
{
    public function success(array $data = []);
    public function error(string $message, int $status = 500);
}