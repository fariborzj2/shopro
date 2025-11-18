<?php

namespace App\Controllers\Admin;

class ApiController
{
    /**
     * Handle the image upload for TinyMCE.
     */
    public function uploadImage()
    {
        header('Content-Type: application/json');

        // The main application already handles session_start() and CSRF verification.
        // We just need to check for admin authentication.
        if (!isset($_SESSION['admin_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Authentication required.']);
            exit;
        }

        // --- Configuration ---
        $uploadDir = PROJECT_ROOT . '/public/uploads/images/';
        $uploadUrl = '/uploads/images/';

        // --- Ensure the upload directory exists ---
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create upload directory.']);
                exit;
            }
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        // --- File Handling ---
        if (!isset($_FILES['file'])) {
            http_response_code(400);
            echo json_encode(['error' => 'No file was uploaded.']);
            exit;
        }

        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        // Check for upload errors
        if ($fileError !== UPLOAD_ERR_OK) {
            http_response_code(500);
            echo json_encode(['error' => 'An error occurred during the upload.']);
            exit;
        }

        // --- Validation ---
        if ($fileSize > $maxFileSize) {
            http_response_code(413);
            echo json_encode(['error' => 'File size exceeds the limit of 5 MB.']);
            exit;
        }

        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            http_response_code(415);
            echo json_encode(['error' => 'Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions)]);
            exit;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileTmpName);
        finfo_close($finfo);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            http_response_code(415);
            echo json_encode(['error' => 'Invalid file content (MIME type mismatch).']);
            exit;
        }

        if (getimagesize($fileTmpName) === false) {
            http_response_code(415);
            echo json_encode(['error' => 'The uploaded file is not a valid image.']);
            exit;
        }

        // --- Generate a unique filename ---
        $newFileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $fileExtension;
        $destination = $uploadDir . $newFileName;

        // --- Move the file ---
        if (move_uploaded_file($fileTmpName, $destination)) {
            $location = $uploadUrl . $newFileName;
            // TinyMCE expects the location in a specific JSON format
            echo json_encode(['location' => $location]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to move the uploaded file.']);
        }

        // We exit here to prevent any further output
        exit();
    }
}
