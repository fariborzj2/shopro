<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Review
{
    public static function create($data)
    {
        $sql = "INSERT INTO reviews (product_id, user_id, name, mobile, rating, comment, status)
                VALUES (:product_id, :user_id, :name, :mobile, :rating, :comment, :status)";
        Database::query($sql, $data);
        return true;
    }

    public static function findByProductId($product_id)
    {
        $sql = "SELECT r.*, u.name as user_name
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.product_id = :product_id AND r.status = 'approved'
                ORDER BY r.created_at DESC";
        $stmt = Database::query($sql, ['product_id' => $product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findAll()
    {
        $sql = "SELECT r.*, p.name_fa as product_name
                FROM reviews r
                JOIN products p ON r.product_id = p.id
                ORDER BY r.created_at DESC";
        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM reviews WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE reviews SET status = :status, admin_reply = :admin_reply WHERE id = :id";
        Database::query($sql, $data);
        return true;
    }

    public static function delete($id)
    {
        Database::query("DELETE FROM reviews WHERE id = :id", ['id' => $id]);
        return true;
    }

    public static function findLatestHighRated($limit)
    {
        $sql = "SELECT r.*, u.name as user_name
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.status = 'approved' AND r.rating >= 4
                ORDER BY r.created_at DESC
                LIMIT :limit";
        $stmt = Database::query($sql, ['limit' => $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
