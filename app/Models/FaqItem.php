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
        $sql = "INSERT INTO faq_items (question, answer, type, status, position) VALUES (:question, :answer, :type, :status, :position)";
        if (!isset($data['position'])) {
            $data['position'] = 0;
        }
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE faq_items SET question = :question, answer = :answer, type = :type, status = :status, position = :position WHERE id = :id";
        if (!isset($data['position'])) {
            $data['position'] = 0;
        }
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

    public static function updateOrder(array $ids)
    {
        if (empty($ids)) {
            return false;
        }

        $case_sql = "";
        $params = [];
        foreach ($ids as $position => $id) {
            $case_sql .= "WHEN ? THEN ? ";
            $params[] = (int) $id;
            $params[] = $position;
        }

        $id_list = implode(',', array_fill(0, count($ids), '?'));

        $sql = "UPDATE faq_items SET position = CASE id {$case_sql} END WHERE id IN ({$id_list})";

        // Add the IDs for the IN clause to the params array
        foreach ($ids as $id) {
            $params[] = (int) $id;
        }

        Database::query($sql, $params);
        return true;
    }

    public static function findAllFiltered($type = null)
    {
        $sql = "SELECT * FROM faq_items";
        $params = [];

        if ($type) {
            $sql .= " WHERE type = :type";
            $params['type'] = $type;
        }

        $sql .= " ORDER BY position ASC";

        $stmt = Database::query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
