<?php

namespace App\Models;

use App\Core\Database;
use App\Core\SitemapGenerator;
use PDO;

class BlogPost
{
    /**
     * Get a paginated list of blog posts.
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function paginated($limit, $offset)
    {
        $sql = "SELECT bp.*, bc.name_fa as category_name, a.name as author_name
                FROM blog_posts bp
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                LEFT JOIN admins a ON bp.author_id = a.id
                ORDER BY bp.published_at DESC, bp.created_at DESC
                LIMIT :limit OFFSET :offset";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the total count of blog posts.
     *
     * @return int
     */
    public static function count()
    {
        $stmt = Database::query("SELECT COUNT(id) FROM blog_posts");
        return (int) $stmt->fetchColumn();
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
        $sql = "SELECT t.name, t.slug
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
            list($jy, $jm, $jd) = explode('/', $data['published_at']);
            list($gy, $gm, $gd) = jalali_to_gregorian((int)$jy, (int)$jm, (int)$jd);
            $published_at_gregorian = "$gy-$gm-$gd 00:00:00";

            if (strtotime($published_at_gregorian) > time()) {
                $status = 'scheduled';
            }
            $published_at = $published_at_gregorian;
        } elseif ($status === 'published') {
            $published_at = date('Y-m-d H:i:s');
        }

        $sql = "INSERT INTO blog_posts (category_id, author_id, title, slug, content, excerpt, status, published_at)
                VALUES (:category_id, :author_id, :title, :slug, :content, :excerpt, :status, :published_at)";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'category_id' => $data['category_id'],
            'author_id' => $data['author_id'],
            'title' => htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8'),
            'slug' => $data['slug'],
            'content' => htmlspecialchars($data['content'], ENT_QUOTES, 'UTF-8'),
            'excerpt' => htmlspecialchars($data['excerpt'], ENT_QUOTES, 'UTF-8'),
            'status' => $status,
            'published_at' => $published_at
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
            list($jy, $jm, $jd) = explode('/', $data['published_at']);
            list($gy, $gm, $gd) = jalali_to_gregorian((int)$jy, (int)$jm, (int)$jd);
            $published_at_gregorian = "$gy-$gm-$gd 00:00:00";

            if (strtotime($published_at_gregorian) > time()) {
                $status = 'scheduled';
            }
            $published_at = $published_at_gregorian;
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
                SET category_id = :category_id, author_id = :author_id, title = :title, slug = :slug, content = :content, excerpt = :excerpt, status = :status, published_at = :published_at
                WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'category_id' => $data['category_id'],
            'author_id' => $data['author_id'],
            'title' => htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8'),
            'slug' => $data['slug'],
            'content' => htmlspecialchars($data['content'], ENT_QUOTES, 'UTF-8'),
            'excerpt' => htmlspecialchars($data['excerpt'], ENT_QUOTES, 'UTF-8'),
            'status' => $status,
            'published_at' => $published_at
        ]);

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

    public static function findAllPublished($limit, $offset, $search = null, $category_id = null, $tag_id = null)
    {
        $params = [];
        $sql = "SELECT bp.*, bc.name_fa as category_name, bc.slug as category_slug, a.name as author_name
                FROM blog_posts bp
                LEFT JOIN blog_categories bc ON bp.category_id = bc.id
                LEFT JOIN admins a ON bp.author_id = a.id";
        if ($tag_id) {
            $sql .= " JOIN blog_post_tags bpt ON bp.id = bpt.post_id";
        }
        $sql .= " WHERE bp.status = 'published' AND (bp.published_at IS NULL OR bp.published_at <= NOW())";

        if ($search) {
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
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function countAllPublished($search = null, $category_id = null, $tag_id = null)
    {
        $params = [];
        $sql = "SELECT COUNT(bp.id) FROM blog_posts bp";
        if ($tag_id) {
            $sql .= " JOIN blog_post_tags bpt ON bp.id = bpt.post_id";
        }
        $sql .= " WHERE bp.status = 'published' AND (bp.published_at IS NULL OR bp.published_at <= NOW())";

        if ($search) {
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

        $stmt = Database::query($sql, $params);
        return (int) $stmt->fetchColumn();
    }

    public static function findRelatedPosts($post_id, $limit = 5)
    {
        $sql = "SELECT bp.*, COUNT(bpt.tag_id) as common_tags
                FROM blog_post_tags bpt
                JOIN blog_posts bp ON bpt.post_id = bp.id
                WHERE bpt.tag_id IN (SELECT tag_id FROM blog_post_tags WHERE post_id = :post_id)
                AND bp.id != :post_id
                GROUP BY bp.id
                ORDER BY common_tags DESC
                LIMIT :limit";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
