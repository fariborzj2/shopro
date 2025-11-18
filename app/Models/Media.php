<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Media
{
    /**
     * Get all media records.
     */
    public static function all()
    {
        $stmt = Database::query("SELECT * FROM media_uploads ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get a paginated list of media records.
     */
    public static function paginated($limit, $offset)
    {
        $sql = "SELECT m.*, a.name as admin_name
                FROM media_uploads m
                LEFT JOIN admins a ON m.uploaded_by_admin_id = a.id
                ORDER BY m.created_at DESC
                LIMIT :limit OFFSET :offset";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Get the total count of media records.
     */
    public static function count()
    {
        $stmt = Database::query("SELECT COUNT(id) FROM media_uploads");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Find a media record by its ID.
     */
    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM media_uploads WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Create a new media upload record.
     */
    public static function create($data)
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO media_uploads (file_path, context, uploaded_by_admin_id) VALUES (:file_path, :context, :uploaded_by_admin_id)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'file_path' => $data['file_path'],
            'context' => $data['context'],
            'uploaded_by_admin_id' => $data['uploaded_by_admin_id'] ?? null,
        ]);
        return $db->lastInsertId();
    }

    /**
     * Delete a media record by its ID.
     */
    public static function delete($id)
    {
        Database::query("DELETE FROM media_uploads WHERE id = :id", ['id' => $id]);
        return true;
    }

    /**
     * Delete a media upload record by its file path.
     */
    public static function deleteByPath($filePath)
    {
        $sql = "DELETE FROM media_uploads WHERE file_path = :file_path";
        Database::query($sql, ['file_path' => $filePath]);
        return true;
    }
}
