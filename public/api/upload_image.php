<?php
// public/api/upload_image.php

header('Content-Type: application/json');

// --- Basic Security Checks ---
// This is a simplified check. In a real-world scenario, you MUST implement
// robust authentication and authorization to ensure only logged-in admins can upload.
/*
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Authentication required.']);
    exit;
}
*/

// --- Configuration ---
// The path is relative to the project root, but the URL should be absolute from the domain.
$uploadDir = __DIR__ . '/../uploads/images/';
$uploadUrl = '/uploads/images/';

// --- Ensure the upload directory exists ---
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
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
// 1. File Size
if ($fileSize > $maxFileSize) {
    http_response_code(413); // Payload Too Large
    echo json_encode(['error' => 'File size exceeds the limit of 5 MB.']);
    exit;
}

// 2. File Extension
$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
if (!in_array($fileExtension, $allowedExtensions)) {
    http_response_code(415); // Unsupported Media Type
    echo json_encode(['error' => 'Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions)]);
    exit;
}

// 3. MIME Type (more reliable than extension)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $fileTmpName);
finfo_close($finfo);
$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($mimeType, $allowedMimeTypes)) {
    http_response_code(415);
    echo json_encode(['error' => 'Invalid file content (MIME type mismatch).']);
    exit;
}

// 4. Check if the file is a valid image
if (getimagesize($fileTmpName) === false) {
    http_response_code(415);
    echo json_encode(['error' => 'The uploaded file is not a valid image.']);
    exit;
}


// --- Generate a unique filename to prevent overwriting ---
// Example: timestamp_randomstring.extension
$newFileName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $fileExtension;
$destination = $uploadDir . $newFileName;

// --- Move the file ---
if (move_uploaded_file($fileTmpName, $destination)) {
    // The URL that TinyMCE needs to embed the image.
    $location = $uploadUrl . $newFileName;

    // TinyMCE's expected JSON response
    echo json_encode(['location' => $location]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to move the uploaded file.']);
}

?>
