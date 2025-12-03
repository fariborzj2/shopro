<?php

namespace App\Controllers\Admin;

use App\Models\Media;
use App\Core\Paginator;

class MediaController
{
    /**
     * Show a file manager style interface for media uploads.
     */
    public function index()
    {
        // Define Root Path
        $rootPath = PROJECT_ROOT . '/public/uploads';

        // Get current requested relative path
        $currentPath = $_GET['folder'] ?? '';

        // Sanitization: Remove .. and ensure it doesn't start with /
        $currentPath = str_replace(['../', '..\\'], '', $currentPath);
        $currentPath = trim($currentPath, '/\\');

        // Build Full Path
        $fullPath = $rootPath . ($currentPath ? '/' . $currentPath : '');

        // Safety Check: Ensure we are still inside public/uploads
        // realpath returns false if file doesn't exist, so we check file_exists first or handle false
        $realFullPath = realpath($fullPath);
        $realRootPath = realpath($rootPath);

        if ($realFullPath === false || !str_starts_with($realFullPath, $realRootPath)) {
            // Fallback to root if malicious path or not found
            $currentPath = '';
            $fullPath = $rootPath;
        }

        if (!is_dir($fullPath)) {
             // Create if missing (rare case if root is deleted) or reset
             if (!file_exists($rootPath)) mkdir($rootPath, 0755, true);
             $currentPath = '';
             $fullPath = $rootPath;
        }

        // Scan Directory
        $items = scandir($fullPath);
        $folders = [];
        $files = [];

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $path = $fullPath . '/' . $item;
            $relativePath = ($currentPath ? $currentPath . '/' : '') . $item;

            if (is_dir($path)) {
                $folders[] = [
                    'name' => $item,
                    'path' => $relativePath,
                    'count' => count(scandir($path)) - 2 // Simple count
                ];
            } else {
                // It's a file
                $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'mp4', 'pdf', 'zip'];

                if (in_array($extension, $allowedExtensions)) {
                     $files[] = [
                        'name' => $item,
                        'path' => '/uploads/' . $relativePath, // Web accessible path
                        'real_path' => $path, // Physical path for internal use if needed
                        'extension' => $extension,
                        'size' => $this->formatSize(filesize($path)),
                        'date' => filemtime($path)
                     ];
                }
            }
        }

        // Breadcrumbs
        $breadcrumbs = [];
        if ($currentPath) {
            $parts = explode('/', $currentPath);
            $crumbPath = '';
            foreach ($parts as $part) {
                $crumbPath .= ($crumbPath ? '/' : '') . $part;
                $breadcrumbs[] = ['name' => $part, 'path' => trim($crumbPath, '/')];
            }
        }

        return view('main', 'media/index', [
            'title' => 'کتابخانه رسانه',
            'currentPath' => $currentPath,
            'folders' => $folders,
            'files' => $files,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Delete a media file and its database record using path.
     */
    public function deleteItem()
    {
        $path = $_POST['path'] ?? '';

        // Basic validation
        if (empty($path)) {
            redirect_back_with_error('مسیر فایل نامعتبر است.');
        }

        // Security: Prevent directory traversal
        if (strpos($path, '..') !== false) {
             redirect_back_with_error('مسیر فایل غیرمجاز است.');
        }

        // Normalize path: remove leading slashes and /uploads prefix if present
        $cleanPath = trim($path, '/');
        if (str_starts_with($cleanPath, 'uploads/')) {
            $cleanPath = substr($cleanPath, 8); // Remove 'uploads/'
        }

        // Construct full physical path
        $fullPath = PROJECT_ROOT . '/public/uploads/' . $cleanPath;

        // Construct DB path (usually starts with /uploads/...)
        $dbPath = '/uploads/' . $cleanPath;

        if (file_exists($fullPath) && is_file($fullPath)) {
            // Delete physical file
            if (@unlink($fullPath)) {
                // Delete from DB
                Media::deleteByPath($dbPath);
                redirect_with_success(url('media?folder=' . dirname($cleanPath)), 'فایل با موفقیت حذف شد.');
            } else {
                 redirect_back_with_error('خطا در حذف فایل از سرور.');
            }
        } else {
            redirect_back_with_error('فایل یافت نشد یا قبلاً حذف شده است.');
        }
    }

    /**
     * Delete a media file by ID (Legacy support).
     */
    public function delete($id)
    {
        $media = Media::find($id);
        if ($media) {
            // 1. Delete the physical file
            $filePath = PROJECT_ROOT . '/public' . $media->file_path;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }

            // 2. Delete the database record
            Media::delete($id);
        }

        header('Location: ' . url('media'));
        exit();
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
