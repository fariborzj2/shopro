<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class FaqItem
{
    public static function all()
    {
        $stmt = Database::query("SELECT * FROM faq_items ORDER BY position ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getActive()
    {
        $stmt = Database::query("SELECT * FROM faq_items WHERE status = 'active' ORDER BY position ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM faq_items WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $sql = "INSERT INTO faq_items (question, answer, status, position) VALUES (:question, :answer, :status, :position)";
        Database::query($sql, [
            'question' => $data['question'],
            'answer' => $data['answer'],
            'status' => $data['status'],
            'position' => $data['position'] ?? 0,
        ]);
        return true;
    }

    public static function update($id, $data)
    {
        $sql = "UPDATE faq_items SET question = :question, answer = :answer, status = :status, position = :position WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'question' => $data['question'],
            'answer' => $data['answer'],
            'status' => $data['status'],
            'position' => $data['position'] ?? 0,
        ]);
        return true;
    }

    public static function delete($id)
    {
        Database::query("DELETE FROM faq_items WHERE id = :id", ['id' => $id]);
        return true;
    }
}
