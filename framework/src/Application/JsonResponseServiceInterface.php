<?php

namespace R2Packages\Framework\Application;

interface JsonResponseServiceInterface
{
    public function success(array $data = []);
    public function error(string $message, int $status = 500);
}