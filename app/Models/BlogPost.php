<?php

namespace App\Models;

use App\Core\Database;
use App\Core\SitemapGenerator;
use PDO;

class BlogPost
{
    /**
     * Get a paginated list of blog posts with total count in one query.
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @param string|null $sort
     * @param string|null $dir
     * @return array
     */
    public static function paginatedWithCount($limit, $offset, $search = null, $sort = null, $dir = 'desc')
    {
        $params = [];
        $sql = "SELECT SQL_CALC_FOUND_ROWS bp.*,
                bc.name_fa as category_name,
                a.name as author_name,
                (SELECT COUNT(*) FROM blog_post_comments bpc WHERE bpc.post_id = bp.id) as comments_count
                FROM blog_posts bp
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                LEFT JOIN admins a ON bp.author_id = a.id";

        if ($search) {
            $sql .= " WHERE bp.title LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        // Validate direction
        $dir = strtolower($dir) === 'asc' ? 'ASC' : 'DESC';

        // Sorting logic
        switch ($sort) {
            case 'views_count':
                $sql .= " ORDER BY bp.views_count $dir";
                break;
            case 'comments_count':
                $sql .= " ORDER BY comments_count $dir";
                break;
            default:
                // Default sort: Published At DESC (for list consistency)
                $sql .= " ORDER BY bp.published_at DESC, bp.created_at DESC";
                break;
        }

        $sql .= " LIMIT :limit OFFSET :offset";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch the total count using FOUND_ROWS()
        $total_count = (int) $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();

        return ['posts' => $posts, 'total_count' => $total_count];
    }

    /**
     * Get all published blog posts for the sitemap.
     *
     * @return array
     */
    public static function getAllPublished()
    {
        $sql = "SELECT slug, created_at, updated_at
                FROM blog_posts
                WHERE status = 'published'
                ORDER BY published_at DESC";
        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a single blog post by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM blog_posts WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all tags associated with a specific post.
     *
     * @param int $post_id
     * @return array
     */
    public static function getTagsByPostId($post_id)
    {
        $sql = "SELECT t.id, t.name, t.slug
                FROM blog_tags t
                JOIN blog_post_tags bpt ON t.id = bpt.tag_id
                WHERE bpt.post_id = :post_id";
        $stmt = Database::query($sql, ['post_id' => $post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getFaqItemsByPostId($post_id)
    {
        $sql = "SELECT faq_item_id FROM blog_post_faq_items WHERE post_id = :post_id";
        $stmt = Database::query($sql, ['post_id' => $post_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Find most viewed posts.
     *
     * @param int $limit
     * @param int|null $days
     * @return array
     */
    public static function findMostViewed($limit = 5, $days = null)
    {
        // Select only necessary columns to avoid memory issues with large content
        $sql = "SELECT id, title, slug, excerpt, published_at, image_url, views_count FROM blog_posts WHERE status = 'published' AND (published_at IS NULL OR published_at <= NOW())";

        if ($days) {
            $sql .= " AND published_at >= :cutoff_date";
        }

        $sql .= " ORDER BY views_count DESC LIMIT :limit";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        if ($days) {
            $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
            $stmt->bindValue(':cutoff_date', $cutoff_date);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find editor's picks.
     *
     * @param int $limit
     * @return array
     */
    public static function findEditorsPicks($limit = 5)
    {
        $sql = "SELECT id, title, slug, excerpt, published_at, image_url, is_editors_pick FROM blog_posts WHERE status = 'published' AND is_editors_pick = 1 AND (published_at IS NULL OR published_at <= NOW()) ORDER BY published_at DESC LIMIT :limit";
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find all published posts.
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @param int|null $category_id
     * @return array
     */
    public static function findAllPublished($limit = 10, $offset = 0, $search = null, $category_id = null)
    {
       $result = self::findAllPublishedWithCount($limit, $offset, $search, $category_id);
       return $result['posts'];
    }

    /**
     * Get top authors based on post count.
     *
     * @param int $limit
     * @return array
     */
    public static function getTopAuthors($limit = 5)
    {
         $sql = "SELECT a.id, a.name, a.email, COUNT(bp.id) as posts_count
                 FROM admins a
                 JOIN blog_posts bp ON a.id = bp.author_id
                 WHERE bp.status = 'published' AND (bp.published_at IS NULL OR bp.published_at <= NOW())
                 GROUP BY a.id, a.name, a.email
                 ORDER BY posts_count DESC
                 LIMIT :limit";

         $pdo = Database::getConnection();
         $stmt = $pdo->prepare($sql);
         $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get posts by category slug.
     *
     * @param string $slug
     * @param int $limit
     * @return array
     */
    public static function getPostsByCategorySlug($slug, $limit = 4)
    {
         $sql = "SELECT bp.id, bp.title, bp.slug, bp.excerpt, bp.image_url, bp.published_at, bp.views_count,
                bc.name_fa as category_name, bc.slug as category_slug, a.name as author_name
                FROM blog_posts bp
                JOIN blog_categories bc ON bp.category_id = bc.id
                LEFT JOIN admins a ON bp.author_id = a.id
                WHERE bc.slug = :slug
                AND bp.status = 'published'
                AND (bp.published_at IS NULL OR bp.published_at <= NOW())
                ORDER BY bp.published_at DESC
                LIMIT :limit";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find related posts.
     *
     * @param int $post_id
     * @param int $limit
     * @return array
     */
    public static function findRelatedPosts($post_id, $limit = 5)
    {
        // Optimized to exclude content
        $sql = "SELECT bp.id, bp.title, bp.slug, bp.excerpt, bp.image_url, bp.published_at, COUNT(bpt.tag_id) as common_tags
                FROM blog_post_tags bpt
                JOIN blog_posts bp ON bpt.post_id = bp.id
                WHERE bpt.tag_id IN (SELECT tag_id FROM blog_post_tags WHERE post_id = :post_id)
                AND bp.id != :exclude_id
                GROUP BY bp.id
                ORDER BY common_tags DESC
                LIMIT :limit";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindValue(':exclude_id', $post_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Find all published posts with count.
     *
     * @param int $limit
     * @param int $offset
     * @param string|null $search
     * @param int|null $category_id
     * @param int|null $tag_id
     * @return array
     */
    public static function findAllPublishedWithCount($limit, $offset, $search = null, $category_id = null, $tag_id = null)
    {
        $params = [];
        // Optimized query to exclude 'content' for listing pages to save memory
        $sql = "SELECT SQL_CALC_FOUND_ROWS bp.id, bp.title, bp.slug, bp.excerpt, bp.published_at, bp.created_at, bp.image_url, bp.views_count,
                bc.name_fa as category_name, bc.slug as category_slug, a.name as author_name, a.role as author_role
                FROM blog_posts bp
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                LEFT JOIN admins a ON bp.author_id = a.id";
        if ($tag_id) {
            $sql .= " JOIN blog_post_tags bpt ON bp.id = bpt.post_id";
        }
        $sql .= " WHERE bp.status = 'published' AND (bp.published_at IS NULL OR bp.published_at <= NOW())";

        if ($search) {
            // For searching, we still need to check content, but we don't select it
            $sql .= " AND (bp.title LIKE :search OR bp.content LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        if ($category_id) {
            $sql .= " AND bp.category_id = :category_id";
            $params['category_id'] = $category_id;
        }
        if ($tag_id) {
            $sql .= " AND bpt.tag_id = :tag_id";
            $params['tag_id'] = $tag_id;
        }

        $sql .= " ORDER BY bp.published_at DESC LIMIT :limit OFFSET :offset";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $total_count = (int) $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();

        return ['posts' => $posts, 'total_count' => $total_count];
    }

    public static function findBySlug($slug)
    {
        $sql = "SELECT bp.*, bc.name_fa as category_name, bc.slug as category_slug, a.name as author_name
                FROM blog_posts bp
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                LEFT JOIN admins a ON bp.author_id = a.id
                WHERE bp.slug = :slug AND bp.status = 'published' AND (bp.published_at IS NULL OR bp.published_at <= NOW())";
        $stmt = Database::query($sql, ['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Create a new blog post.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $status = $data['status'];
        $published_at = null;

        if (!empty($data['published_at'])) {
            // Check if the date is already in Gregorian format (contains -)
            if (strpos($data['published_at'], '-') !== false) {
                $published_at = $data['published_at'];
            } elseif (strpos($data['published_at'], '/') !== false) {
                // Only attempt explode if slash exists to avoid "Undefined array key 1"
                $parts = explode('/', $data['published_at']);
                if (count($parts) === 3) {
                     list($jy, $jm, $jd) = $parts;
                     list($gy, $gm, $gd) = jalali_to_gregorian((int)$jy, (int)$jm, (int)$jd);
                     $published_at = "$gy-$gm-$gd 00:00:00";
                } else {
                     // Fallback or error? For now, ignore invalid format or set to now
                     // Better to leave null or handle gracefully
                }
            }

            if ($published_at && strtotime($published_at) > time()) {
                $status = 'scheduled';
            }
        } elseif ($status === 'published') {
            $published_at = date('Y-m-d H:i:s');
        }

        $sql = "INSERT INTO blog_posts (category_id, author_id, title, slug, content, excerpt, image_url, status, published_at, is_editors_pick, meta_title, meta_description, meta_keywords)
                VALUES (:category_id, :author_id, :title, :slug, :content, :excerpt, :image_url, :status, :published_at, :is_editors_pick, :meta_title, :meta_description, :meta_keywords)";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'category_id' => $data['category_id'],
            'author_id' => $data['author_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'],
            'image_url' => $data['image_url'] ?? null,
            'status' => $status,
            'published_at' => $published_at,
            'is_editors_pick' => isset($data['is_editors_pick']) ? 1 : 0,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => isset($data['meta_keywords']) ? (is_array($data['meta_keywords']) ? json_encode($data['meta_keywords'], JSON_UNESCAPED_UNICODE) : $data['meta_keywords']) : null
        ]);

        $post_id = $pdo->lastInsertId();

        // Regenerate sitemap if the post is published
        if ($data['status'] === 'published') {
            SitemapGenerator::generate();
        }

        return $post_id;
    }

    /**
     * Update an existing blog post.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        $status = $data['status'];
        $published_at = null;

        if (!empty($data['published_at'])) {
            // Check if the date is already in Gregorian format (contains -)
            if (strpos($data['published_at'], '-') !== false) {
                $published_at = $data['published_at'];
            } elseif (strpos($data['published_at'], '/') !== false) {
                // Only attempt explode if slash exists to avoid "Undefined array key 1"
                $parts = explode('/', $data['published_at']);
                if (count($parts) === 3) {
                     list($jy, $jm, $jd) = $parts;
                     list($gy, $gm, $gd) = jalali_to_gregorian((int)$jy, (int)$jm, (int)$jd);
                     $published_at = "$gy-$gm-$gd 00:00:00";
                }
            }

            if ($published_at && strtotime($published_at) > time()) {
                $status = 'scheduled';
            }
        } elseif ($status === 'published') {
            $current = self::find($id);
            // Only set publish date if it's not already published
            if ($current['status'] !== 'published') {
                $published_at = date('Y-m-d H:i:s');
            } else {
                $published_at = $current['published_at'];
            }
        }

        $sql = "UPDATE blog_posts
                SET category_id = :category_id, author_id = :author_id, title = :title, slug = :slug, content = :content, excerpt = :excerpt, status = :status, published_at = :published_at, is_editors_pick = :is_editors_pick, meta_title = :meta_title, meta_description = :meta_description, meta_keywords = :meta_keywords";

        $params = [
            'id' => $id,
            'category_id' => $data['category_id'],
            'author_id' => $data['author_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'],
            'status' => $status,
            'published_at' => $published_at,
            'is_editors_pick' => isset($data['is_editors_pick']) ? 1 : 0,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => isset($data['meta_keywords']) ? (is_array($data['meta_keywords']) ? json_encode($data['meta_keywords'], JSON_UNESCAPED_UNICODE) : $data['meta_keywords']) : null
        ];

        if (array_key_exists('image_url', $data)) {
            $sql .= ", image_url = :image_url";
            $params['image_url'] = $data['image_url'];
        }

        $sql .= " WHERE id = :id";

        Database::query($sql, $params);

        // Regenerate sitemap if the post's status has changed to published or was already published
        if ($data['status'] === 'published') {
            SitemapGenerator::generate();
        }

        return true;
    }

    /**
     * Delete a blog post.
     *
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        Database::query("DELETE FROM blog_posts WHERE id = :id", ['id' => $id]);
        return true;
    }

    /**
     * Sync tags for a blog post.
     *
     * @param int $post_id
     * @param array $tag_ids
     */
    public static function syncTags($post_id, $tag_ids = [])
    {
        // First, remove all existing tags for the post
        Database::query("DELETE FROM blog_post_tags WHERE post_id = :post_id", ['post_id' => $post_id]);

        // Then, add the new tags
        if (!empty($tag_ids)) {
            $sql = "INSERT INTO blog_post_tags (post_id, tag_id) VALUES ";
            $params = [];
            $placeholders = [];
            foreach ($tag_ids as $tag_id) {
                $placeholders[] = '(?, ?)';
                $params[] = $post_id;
                $params[] = $tag_id;
            }
            $sql .= implode(', ', $placeholders);
            Database::query($sql, $params);
        }
    }

    public static function syncFaqItems($post_id, $faq_ids = [])
    {
        // First, remove all existing FAQ items for the post
        Database::query("DELETE FROM blog_post_faq_items WHERE post_id = :post_id", ['post_id' => $post_id]);

        // Then, add the new FAQ items
        if (!empty($faq_ids)) {
            $sql = "INSERT INTO blog_post_faq_items (post_id, faq_item_id) VALUES ";
            $params = [];
            $placeholders = [];
            foreach ($faq_ids as $faq_id) {
                $placeholders[] = '(?, ?)';
                $params[] = $post_id;
                $params[] = $faq_id;
            }
            $sql .= implode(', ', $placeholders);
            Database::query($sql, $params);
        }
    }

    /**
     * Increment the view count for a blog post.
     *
     * @param int $id
     * @return bool
     */
    public static function incrementViews($id)
    {
        $sql = "UPDATE blog_posts SET views_count = views_count + 1 WHERE id = :id";
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getSearchSuggestions($term, $limit = 5)
    {
        $sql = "SELECT title, slug FROM blog_posts
                WHERE status = 'published'
                AND (published_at IS NULL OR published_at <= NOW())
                AND title LIKE :term
                ORDER BY published_at DESC
                LIMIT :limit";
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':term', '%' . $term . '%');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
