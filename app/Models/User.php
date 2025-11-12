<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class User
{
    /**
     * Get all users from the database.
     *
     * @return array
     */
    public static function all()
    {
        $stmt = Database::query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a user by their ID.
     *
     * @param int $id
     * @return mixed
     */
    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM users WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $sql = "INSERT INTO users (name, mobile, status) VALUES (:name, :mobile, :status)";
        Database::query($sql, [
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'status' => $data['status']
        ]);
        return true;
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        $sql = "UPDATE users SET name = :name, mobile = :mobile, status = :status WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'status' => $data['status']
        ]);
        return true;
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        Database::query("DELETE FROM users WHERE id = :id", ['id' => $id]);
        return true;
    }
}
