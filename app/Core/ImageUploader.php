<?php

namespace App\Core;

class ImageUploader
{
    private $allowedExtensions = ['jpeg', 'jpg', 'png', 'webp', 'avif'];
    private $maxFileSize = 5 * 1024 * 1024; // 5 MB

    public function upload(array $file, string $subDirectory = ''): ?string
    {
        if (!$this->validate($file)) {
            return null;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);

        // Sanitize the original name to prevent directory traversal issues
        $sanitizedName = preg_replace("/[^a-zA-Z0-9-_\.]/", "", $originalName);
        $randomHash = bin2hex(random_bytes(8));
        $newFileName = $sanitizedName . '-' . $randomHash . '.' . $extension;

        $uploadDir = PROJECT_ROOT . '/public/uploads/';
        if (!empty($subDirectory)) {
            $uploadDir .= rtrim($subDirectory, '/') . '/';
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Return the web-accessible path
            return '/uploads/' . (!empty($subDirectory) ? rtrim($subDirectory, '/') . '/' : '') . $newFileName;
        }

        return null;
    }

    private function validate(array $file): bool
    {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            // Handle error, maybe log it
            return false;
        }

        // Check file size
        if ($file['size'] > $this->maxFileSize) {
            // Handle error, maybe log it
            return false;
        }

        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            // Handle error, maybe log it
            return false;
        }

        // Check MIME type to be more secure
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            return false;
        }

        return true;
    }
}
