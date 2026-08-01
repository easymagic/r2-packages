<?php 

namespace R2Packages\Framework\Infrastructure\Framework\File;

class FileUploadService implements FileUploadServiceInterface{

    function uploadFile(array $file, string $path)
    {
        if (empty($file) || !is_array($file) || empty($file['tmp_name']) || (isset($file['error']) && $file['error'] !== UPLOAD_ERR_OK)) {
            return false;
        }
        $fullPath =  $path; // use current working directory
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
        $filename = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filepath = $fullPath . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            return false;
        }
        $uploadedFile = $path . '/' . $filename; // keep reference to the file with path
        return $uploadedFile;
    }
}