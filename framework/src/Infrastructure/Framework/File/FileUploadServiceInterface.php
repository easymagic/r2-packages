<?php

namespace R2Packages\Framework\Infrastructure\Framework\File;

interface FileUploadServiceInterface
{
    public function uploadFile(array $file, string $path);
}