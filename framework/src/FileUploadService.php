<?php 

namespace R2Packages\Framework;

class FileUploadService {

    function uploadFile($file, $path)
    {
        if (empty($file) || !is_array($file) || empty($file['tmp_name']) || (isset($file['error']) && $file['error'] !== UPLOAD_ERR_OK)) {
            return false;
        }
        $fullPath = ROOT_DIR . '/uploads/' . $path;
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