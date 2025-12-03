<?php

namespace App\Controllers\Admin;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Admin;
use App\Models\BlogTag;
use App\Core\Paginator;
use App\Core\ImageUploader;

class BlogPostsController
{
    const ITEMS_PER_PAGE = 15;

    /**
     * Show a paginated list of all blog posts.
     */
    public function index()
    {
        $current_page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
        $offset = ($current_page - 1) * self::ITEMS_PER_PAGE;

        $search = $_GET['search'] ?? null;
        $sort = $_GET['sort'] ?? null;
        $dir = $_GET['dir'] ?? 'desc';

        $result = BlogPost::paginatedWithCount(self::ITEMS_PER_PAGE, $offset, $search, $sort, $dir);
        $posts = $result["posts"];
        $total_posts = $result["total_count"];

        $url_params = "/admin/blog/posts?";
        if ($search) $url_params .= "search=" . urlencode($search) . "&";
        if ($sort) $url_params .= "sort=" . urlencode($sort) . "&";
        if ($dir) $url_params .= "dir=" . urlencode($dir) . "&";

        // Remove trailing & or ? if empty
        $url_params = rtrim($url_params, "&?");

        $paginator = new Paginator(
            $total_posts,
            self::ITEMS_PER_PAGE,
            $current_page,
            $url_params
        );

        return view("main", "blog/posts/index", [
            "title" => "مدیریت نوشته‌ها",
            "posts" => $posts,
            "paginator" => $paginator,
            "search" => $search,
            "sort" => $sort,
            "dir" => $dir
        ]);
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create()
    {
        $categories = BlogCategory::all();
        $authors = Admin::all();
        $tags = BlogTag::findAll();

        return view("main", "blog/posts/create", [
            "title" => "افزودن نوشته جدید",
            "categories" => $categories,
            "authors" => $authors,
            "tags" => $tags,
            "post" => null
        ]);
    }

    /**
     * Store a new blog post in the database.
     */
    public function store()
    {
        // Server-side validation
        $errors = [];
        if (empty($_POST["title"])) {
            $errors[] = "عنوان نوشته الزامی است.";
        }
        if (empty($_POST["slug"])) {
            $errors[] = "اسلاگ نوشته الزامی است.";
        } elseif (!preg_match('/^[a-z0-9-]+$/', $_POST["slug"])) {
            $errors[] =
                "اسلاگ فقط می‌تواند شامل حروف کوچک انگلیسی، اعداد و خط تیره باشد.";
        }
        if ( empty($_POST["category_id"]) || !BlogCategory::find($_POST["category_id"])) {
            $errors[] = "دسته بندی انتخاب شده معتبر نیست.";
        }

        if (!empty($errors)) {
            return redirect_back_with_error(implode('<br>', $errors));
        }

        // Check for duplicate slug
        if (BlogPost::findBySlug($_POST['slug'])) {
            return redirect_back_with_error('این اسلاگ قبلا استفاده شده است. لطفا اسلاگ دیگری انتخاب کنید.');
        }

        // Use the logged-in admin's ID as the author if not provided, or force it
        // The user reported "Undefined array key author_id".
        // This suggests the form might not be sending it, or it is optional.
        // We should rely on the session for security, or validated input.
        $author_id = $_SESSION["admin_id"] ?? null;
        if (!$author_id) {
            // Should not happen if middleware works, but safety first
            return redirect_back_with_error('خطای احراز هویت: شناسه نویسنده یافت نشد.');
        }

        $data = [
            "category_id" => (int) $_POST["category_id"],
            "author_id" => $author_id,
            "title" => htmlspecialchars($_POST["title"]),
            "slug" => htmlspecialchars($_POST["slug"]),
            "content" => $_POST["content"] ?? "",
            "excerpt" => $_POST["excerpt"] ?? "",
            "status" => $_POST["status"] ?? "draft",
            "published_at" => $_POST["published_at"] ?? null,
            "is_editors_pick" => isset($_POST["is_editors_pick"]) ? 1 : 0,
            "meta_title" => htmlspecialchars($_POST["meta_title"] ?? ""),
            "meta_description" => htmlspecialchars(
                $_POST["meta_description"] ?? ""
            ),
            "meta_keywords" => $_POST["meta_keywords"] ?? null,
            "faq" => array_map(function($item) {
                return [
                    'question' => htmlspecialchars($item['question']),
                    'answer' => htmlspecialchars($item['answer'])
                ];
            }, array_filter($_POST['post_faqs'] ?? [], function($item) {
                return !empty($item['question']) && !empty($item['answer']);
            }))
        ];

        // Handle published_at (Jalali Date or Timestamp)
        if (!empty($_POST["published_at"])) {
            $inputDate = $_POST["published_at"];

            // Check if it's numeric (Timestamp in milliseconds from JS)
            if (is_numeric($inputDate)) {
                // If length > 10, it's likely milliseconds (e.g. 1700000000000)
                // If length <= 10, it's likely seconds (e.g. 1700000000)
                // We cast to float first to handle large strings safely
                $ts = (float) $inputDate;
                if ($ts > 10000000000) {
                    $ts = $ts / 1000;
                }
                $timestamp = (int) $ts;
                $data["published_at"] = date("Y-m-d H:i:s", $timestamp);
            } else {
                // Expected format from persian-datepicker string: YYYY/MM/DD HH:mm:ss
                $parts = preg_split("/[\/\-\s:]/", $inputDate);
                if (count($parts) >= 3) {
                    $jy = (int) $parts[0];
                    $jm = (int) $parts[1];
                    $jd = (int) $parts[2];
                    $h = isset($parts[3]) ? (int) $parts[3] : 0;
                    $m = isset($parts[4]) ? (int) $parts[4] : 0;
                    $s = isset($parts[5]) ? (int) $parts[5] : 0;

                    $gregorian = jalali_to_gregorian($jy, $jm, $jd);
                    $data["published_at"] = sprintf(
                        "%04d-%02d-%02d %02d:%02d:%02d",
                        $gregorian[0],
                        $gregorian[1],
                        $gregorian[2],
                        $h,
                        $m,
                        $s
                    );
                }
            }
        }

        // Image Upload
        $uploader = new ImageUploader();
        if (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] === UPLOAD_ERR_OK
        ) {
            // Use Jalali date for the folder structure
            $dateFolder = \jdate('Y-m-d');
            $data["image_url"] = $uploader->upload(
                $_FILES["image"],
                "blog_posts/" . $dateFolder,
                "uploads/images"
            );
        }

        $post_id = BlogPost::create($data);

        // Sync tags (handle IDs and new Strings)
        $rawTags = $_POST["tags"] ?? [];
        $tagIds = [];
        foreach ($rawTags as $tag) {
            if (strpos($tag, "new:") === 0) {
                // Create new tag
                $tagName = trim(substr($tag, 4));
                // Generate a proper slug, preserving Persian characters
                $slug = trim($tagName);
                $slug = str_replace(' ', '-', $slug);
                $slug = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $slug); // Remove special chars but keep letters/numbers (unicode)
                $slug = preg_replace('/-+/', '-', $slug); // Collapse multiple dashes

                if (empty($slug)) {
                     $slug = 'tag-' . time(); // Fallback to avoid empty slug errors
                }

                // Check by name OR slug to avoid duplicates
                $existing = BlogTag::findBy("name", $tagName);
                if (!$existing) {
                    $existing = BlogTag::findBy("slug", $slug);
                }

                if ($existing) {
                    $tagIds[] = $existing->id;
                } else {
                    try {
                        BlogTag::create([
                            "name" => $tagName,
                            "slug" => $slug,
                            "status" => "active",
                        ]);
                        $newTag = BlogTag::findBy("slug", $slug);
                        if ($newTag) {
                            $tagIds[] = $newTag->id;
                        }
                    } catch (\PDOException $e) {
                        // If race condition or still duplicate, try to find again
                        $existingAgain = BlogTag::findBy("slug", $slug);
                        if ($existingAgain) {
                            $tagIds[] = $existingAgain->id;
                        }
                    }
                }
            } else {
                $tagIds[] = (int) $tag;
            }
        }
        BlogPost::syncTags($post_id, $tagIds);

        header("Location: /admin/blog/posts");
        exit();

    }
    /**
     * Show the form for editing a specific blog post.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $post = BlogPost::find($id);
        if (!$post) {
            redirect_back_with_error("Blog post not found.");
        }

        $categories = BlogCategory::all();
        $authors = Admin::all();
        $tags = BlogTag::findAll();
        $post_tags = BlogPost::getTagsByPostId($id);

        // Fetch FAQ data from JSON column
        $post_faq_objects = [];
        if (!empty($post['faq'])) {
            $post_faq_objects = json_decode($post['faq'], true);
        }

        // Convert gregorian published_at to jalali for the view
        // We don't need to convert here if we are initializing the datepicker with a Gregorian timestamp/date.
        // The datepicker JS will handle the display.
        // if (!empty($post['published_at'])) {
        //     $ts = strtotime($post['published_at']);
        //     list($jy, $jm, $jd) = gregorian_to_jalali(date('Y', $ts), date('m', $ts), date('d', $ts));
        //     $post['published_at_jalali'] = "$jy/$jm/$jd";
        // }

        return view("main", "blog/posts/edit", [
            "title" => "ویرایش نوشته",
            "post" => $post,
            "categories" => $categories,
            "authors" => $authors,
            "tags" => $tags,
            "post_tags" => $post_tags,
            "post_faq_objects" => $post_faq_objects,
        ]);
    }

    /**
     * Update an existing blog post in the database.
     *
     * @param int $id
     */
    public function update($id)
    {
        $post = BlogPost::find($id);
        if (!$post) {
            return redirect_back_with_error("نوشته پیدا نشد.");
        }

        // Authorization check (simple version: only author can edit)
        // In a real app, you might have roles like 'editor' or 'admin'
        // who can edit any post.
        // if ($post['author_id'] !== $_SESSION['admin_id']) {
        //     return redirect_back_with_error('شما اجازه ویرایش این نوشته را ندارید.');
        // }

        // Server-side validation
        $errors = [];
        if (empty($_POST["title"])) {
            $errors[] = "عنوان نوشته الزامی است.";
        }
        if (empty($_POST["slug"])) {
            $errors[] = "اسلاگ نوشته الزامی است.";
        } elseif (!preg_match('/^[a-z0-9-]+$/', $_POST["slug"])) {
            $errors[] =
                "اسلاگ فقط می‌تواند شامل حروف کوچک انگلیسی، اعداد و خط تیره باشد.";
        }
        if (
            empty($_POST["category_id"]) ||
            !BlogCategory::find($_POST["category_id"])
        ) {
            $errors[] = "دسته بندی انتخاب شده معتبر نیست.";
        }

        if (!empty($errors)) {
            return redirect_back_with_error(implode('<br>', $errors));
        }

        // Check for duplicate slug
        $existing = BlogPost::findBySlug($_POST['slug']);
        if ($existing && $existing->id != $id) {
            return redirect_back_with_error('این اسلاگ قبلا توسط نوشته دیگری استفاده شده است.');
        }

        // Ensure author_id is set. Use existing or session.
        // If the form doesn't send author_id, keep the old one or use current admin?
        // Usually, only admins edit, so we might not want to change the author unless explicit.
        // But if the form sends it (e.g. super admin changing author), use it.
        // The error "Undefined array key author_id" suggests it's NOT sent.
        // So we should check if it's set.
        $author_id = isset($_POST["author_id"]) ? (int)$_POST["author_id"] : $post['author_id'];

        $data = [
            "category_id" => (int) $_POST["category_id"],
            "author_id" => $author_id,
            "title" => htmlspecialchars($_POST["title"]),
            "slug" => htmlspecialchars($_POST["slug"]),
            "content" => $_POST["content"] ?? "",
            "excerpt" => $_POST["excerpt"] ?? "",
            "status" => $_POST["status"] ?? "draft",
            "published_at" => $_POST["published_at"] ?? null,
            "is_editors_pick" => isset($_POST["is_editors_pick"]) ? 1 : 0,
            "meta_title" => htmlspecialchars($_POST["meta_title"] ?? ""),
            "meta_description" => htmlspecialchars(
                $_POST["meta_description"] ?? ""
            ),
            "meta_keywords" => $_POST["meta_keywords"] ?? null,
            "faq" => array_map(function($item) {
                return [
                    'question' => htmlspecialchars($item['question']),
                    'answer' => htmlspecialchars($item['answer'])
                ];
            }, array_filter($_POST['post_faqs'] ?? [], function($item) {
                return !empty($item['question']) && !empty($item['answer']);
            }))
        ];

        // Handle published_at (Jalali Date or Timestamp)
        if (!empty($_POST["published_at"])) {
            $inputDate = $_POST["published_at"];

            // Check if it's numeric (Timestamp in milliseconds from JS)
            if (is_numeric($inputDate)) {
                // If length > 10, it's likely milliseconds (e.g. 1700000000000)
                // If length <= 10, it's likely seconds (e.g. 1700000000)
                // We cast to float first to handle large strings safely
                $ts = (float) $inputDate;
                if ($ts > 10000000000) {
                    $ts = $ts / 1000;
                }
                $timestamp = (int) $ts;
                $data["published_at"] = date("Y-m-d H:i:s", $timestamp);
            } else {
                // Expected format from persian-datepicker string: YYYY/MM/DD HH:mm:ss
                $parts = preg_split("/[\/\-\s:]/", $inputDate);
                if (count($parts) >= 3) {
                    $jy = (int) $parts[0];
                    $jm = (int) $parts[1];
                    $jd = (int) $parts[2];
                    $h = isset($parts[3]) ? (int) $parts[3] : 0;
                    $m = isset($parts[4]) ? (int) $parts[4] : 0;
                    $s = isset($parts[5]) ? (int) $parts[5] : 0;

                    $gregorian = jalali_to_gregorian($jy, $jm, $jd);
                    $data["published_at"] = sprintf(
                        "%04d-%02d-%02d %02d:%02d:%02d",
                        $gregorian[0],
                        $gregorian[1],
                        $gregorian[2],
                        $h,
                        $m,
                        $s
                    );
                }
            }
        }

        // Image Upload
        $uploader = new ImageUploader();
        if (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] === UPLOAD_ERR_OK
        ) {
            // Delete old image if exists
            if (!empty($post["image_url"])) {
                @unlink(PROJECT_ROOT . "/public" . $post["image_url"]);
            }

            // Use Jalali date for the folder structure
            $dateFolder = \jdate('Y-m-d');
            $data["image_url"] = $uploader->upload(
                $_FILES["image"],
                "blog_posts/" . $dateFolder,
                "uploads/images"
            );
        }

        BlogPost::update($id, $data);

        // Sync tags
        $rawTags = $_POST["tags"] ?? [];
        $tagIds = [];
        foreach ($rawTags as $tag) {
            if (strpos($tag, "new:") === 0) {
                $tagName = trim(substr($tag, 4));
                // Generate a proper slug, preserving Persian characters
                $slug = trim($tagName);
                $slug = str_replace(' ', '-', $slug);
                $slug = preg_replace('/[^\p{L}\p{N}\-]+/u', '', $slug); // Remove special chars but keep letters/numbers (unicode)
                $slug = preg_replace('/-+/', '-', $slug); // Collapse multiple dashes

                if (empty($slug)) {
                    $slug = 'tag-' . time(); // Fallback
                }

                // Check by name OR slug
                $existing = BlogTag::findBy("name", $tagName);
                if (!$existing) {
                    $existing = BlogTag::findBy("slug", $slug);
                }

                if ($existing) {
                    $tagIds[] = $existing->id;
                } else {
                    try {
                        BlogTag::create([
                            "name" => $tagName,
                            "slug" => $slug,
                            "status" => "active",
                        ]);
                        $newTag = BlogTag::findBy("slug", $slug);
                        if ($newTag) {
                            $tagIds[] = $newTag->id;
                        }
                    } catch (\PDOException $e) {
                         $existingAgain = BlogTag::findBy("slug", $slug);
                         if ($existingAgain) {
                             $tagIds[] = $existingAgain->id;
                         }
                    }
                }
            } else {
                $tagIds[] = (int) $tag;
            }
        }
        BlogPost::syncTags($id, $tagIds);

        header("Location: /admin/blog/posts");
        exit();
    }

    /**
     * Delete a blog post.
     *
     * @param int $id
     */
    public function delete($id)
    {
        BlogPost::delete($id);
        header("Location: /admin/blog/posts");
        exit();
    }

    /**
     * Delete the featured image of a blog post.
     */
    public function deleteImage($id)
    {
        header("Content-Type: application/json");

        $post = BlogPost::find($id);
        if (!$post) {
            echo json_encode([
                "success" => false,
                "message" => "نوشته یافت نشد.",
            ]);
            return;
        }

        if (!empty($post["image_url"])) {
            // Delete physical file
            @unlink(PROJECT_ROOT . "/public" . $post["image_url"]);

            // Update DB
            BlogPost::updateImage($id, null);

            echo json_encode([
                "success" => true,
                "message" => "تصویر شاخص حذف شد.",
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "تصویری برای حذف وجود ندارد.",
            ]);
        }
        exit();
    }
}
