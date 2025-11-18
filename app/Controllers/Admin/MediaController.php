<?php

namespace App\Controllers\Admin;

use App\Models\Media;
use App\Core\Paginator;

class MediaController
{
    const ITEMS_PER_PAGE = 30;

    /**
     * Show a paginated list of all media uploads.
     */
    public function index()
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalItems = Media::count();

        $paginator = new Paginator($totalItems, self::ITEMS_PER_PAGE, $currentPage, '/admin/media');
        $mediaItems = Media::paginated(self::ITEMS_PER_PAGE, $paginator->getOffset());

        return view('main', 'media/index', [
            'title' => 'کتابخانه رسانه',
            'mediaItems' => $mediaItems,
            'paginator' => $paginator
        ]);
    }

    /**
     * Delete a media file and its database record.
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
}
