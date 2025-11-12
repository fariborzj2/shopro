<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Page
{
    public static function all()
    {
        $stmt = Database::query("SELECT * FROM pages ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM pages WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findBySlug($slug)
    {
        $stmt = Database::query("SELECT * FROM pages WHERE slug = :slug AND status = 'published'", ['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $sql = "INSERT INTO pages (title, slug, content, status) VALUES (:title, :slug, :content, :status)";
        Database::query($sql, [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'status' => $data['status'],
        ]);
        return true;
    }

    public static function update($id, $data)
    {
        $sql = "UPDATE pages SET title = :title, slug = :slug, content = :content, status = :status WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'status' => $data['status'],
        ]);
        return true;
    }

    public static function delete($id)
    {
        Database::query("DELETE FROM pages WHERE id = :id", ['id' => $id]);
        return true;
    }
}
