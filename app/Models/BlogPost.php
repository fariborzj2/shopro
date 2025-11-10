<?php

namespace App\Models;

use App\Core\Database;
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
     * Create a new blog post.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $sql = "INSERT INTO blog_posts (category_id, author_id, title, slug, content, excerpt, status, published_at)
                VALUES (:category_id, :author_id, :title, :slug, :content, :excerpt, :status, :published_at)";
        Database::query($sql, [
            'category_id' => $data['category_id'],
            'author_id' => $data['author_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'],
            'status' => $data['status'],
            'published_at' => ($data['status'] === 'published') ? date('Y-m-d H:i:s') : null
        ]);
        return true;
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
        // Get the current post status to check if it's being published now
        $current = self::find($id);
        $published_at = $current['published_at'];
        if ($current['status'] !== 'published' && $data['status'] === 'published') {
            $published_at = date('Y-m-d H:i:s');
        }

        $sql = "UPDATE blog_posts
                SET category_id = :category_id, author_id = :author_id, title = :title, slug = :slug, content = :content, excerpt = :excerpt, status = :status, published_at = :published_at
                WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'category_id' => $data['category_id'],
            'author_id' => $data['author_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'],
            'status' => $data['status'],
            'published_at' => $published_at
        ]);
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
}
