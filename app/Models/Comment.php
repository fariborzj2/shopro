<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Comment
{
    public static function create($data)
    {
        $sql = "INSERT INTO blog_post_comments (post_id, parent_id, name, email, comment, status)
                VALUES (:post_id, :parent_id, :name, :email, :comment, :status)";
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return $pdo->lastInsertId();
    }

    public static function find($id)
    {
        $sql = "SELECT c.*, p.title as post_title, p.slug as post_slug
                FROM blog_post_comments c
                JOIN blog_posts p ON c.post_id = p.id
                WHERE c.id = :id";
        $stmt = Database::query($sql, ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByPostId($post_id)
    {
        $sql = "SELECT * FROM blog_post_comments WHERE post_id = :post_id AND status = 'approved' ORDER BY created_at DESC";
        $stmt = Database::query($sql, ['post_id' => $post_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return self::buildTree($comments);
    }

    private static function buildTree(array &$elements, $parentId = null)
    {
        $branch = [];
        foreach ($elements as &$element) {
            if ($element['parent_id'] == $parentId) {
                $children = self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
                unset($element);
            }
        }
        return $branch;
    }

    public static function findAll()
    {
        $sql = "SELECT c.*, p.title as post_title
                FROM blog_post_comments c
                JOIN blog_posts p ON c.post_id = p.id
                ORDER BY c.created_at DESC";
        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPaginatedList($page = 1, $perPage = 10, $status = null, $search = null, $sort = 'created_at', $dir = 'desc')
    {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $whereClause = "WHERE 1=1";

        if ($status) {
            $whereClause .= " AND c.status = :status";
            $params['status'] = $status;
        }

        if ($search) {
            $whereClause .= " AND (c.name LIKE :search OR c.email LIKE :search OR c.comment LIKE :search OR p.title LIKE :search)";
            $params['search'] = "%$search%";
        }

        // Whitelist sort columns to prevent SQL injection
        $allowedSorts = ['created_at', 'status', 'name', 'post_title'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        $dir = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';

        // Count total for pagination
        $countSql = "SELECT COUNT(*) FROM blog_post_comments c JOIN blog_posts p ON c.post_id = p.id $whereClause";
        $total = Database::query($countSql, $params)->fetchColumn();

        // Fetch data
        $sql = "SELECT c.*, p.title as post_title, p.slug as post_slug
                FROM blog_post_comments c
                JOIN blog_posts p ON c.post_id = p.id
                $whereClause
                ORDER BY c.$sort $dir
                LIMIT $perPage OFFSET $offset";

        $stmt = Database::query($sql, $params);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'items' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    public static function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $fieldsStr = implode(', ', $fields);
        $data['id'] = $id;

        $sql = "UPDATE blog_post_comments SET $fieldsStr WHERE id = :id";
        Database::query($sql, $data);
        return true;
    }

    public static function delete($id)
    {
        // First, get all child comments
        $children = self::getAllChildren($id);
        $children[] = $id;

        // Then, delete all child comments and the parent comment
        $inQuery = implode(',', array_fill(0, count($children), '?'));
        Database::query("DELETE FROM blog_post_comments WHERE id IN ($inQuery)", $children);

        return true;
    }

    private static function getAllChildren($parentId)
    {
        $children = [];
        $stmt = Database::query("SELECT id FROM blog_post_comments WHERE parent_id = :parent_id", ['parent_id' => $parentId]);
        $directChildren = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($directChildren as $childId) {
            $children[] = $childId;
            $children = array_merge($children, self::getAllChildren($childId));
        }
        return $children;
    }

    public static function updateStatus($id, $status)
    {
        $sql = "UPDATE blog_post_comments SET status = :status WHERE id = :id";
        Database::query($sql, ['id' => $id, 'status' => $status]);
        return true;
    }
}
