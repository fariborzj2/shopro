<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class FaqItem
{
    public static function all()
    {
        return self::findAll();
    }

    public static function findAll()
    {
        $stmt = Database::query("SELECT * FROM faq_items ORDER BY position ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM faq_items WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }
        $inQuery = implode(',', array_fill(0, count($ids), '?'));
        $stmt = Database::query("SELECT * FROM faq_items WHERE id IN ($inQuery)", $ids);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function create($data)
    {
        $sql = "INSERT INTO faq_items (question, answer, type, status) VALUES (:question, :answer, :type, :status)";
        Database::query($sql, $data);
        return true;
    }

    public static function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE faq_items SET question = :question, answer = :answer, type = :type, status = :status WHERE id = :id";
        Database::query($sql, $data);
        return true;
    }

    public static function delete($id)
    {
        Database::query("DELETE FROM faq_items WHERE id = :id", ['id' => $id]);
        return true;
    }

    public static function findAllGroupedByType()
    {
        $stmt = Database::query("SELECT * FROM faq_items WHERE status = 'active' ORDER BY type, position ASC");
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($items as $item) {
            $grouped[$item['type']][] = $item;
        }

        return $grouped;
    }
}
