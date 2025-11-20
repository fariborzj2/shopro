<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Admin
{
    /**
     * Get all admins from the database.
     *
     * @return array
     */
    public static function all()
    {
        $stmt = Database::query("SELECT * FROM admins ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find an admin by ID.
     *
     * @param int $id
     * @return array|false
     */
    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM admins WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find an admin by their username.
     *
     * @param string $username
     * @return array|false
     */
    public static function findByUsername($username)
    {
        $stmt = Database::query("SELECT * FROM admins WHERE username = :username", ['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new admin user.
     *
     * @param array $data
     * @return string|false The ID of the newly created admin, or false on failure.
     */
    public static function create(array $data)
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO admins (username, password_hash, email, name, role, is_super_admin, permissions, status)
                VALUES (:username, :password_hash, :email, :name, :role, :is_super_admin, :permissions, :status)";

        $stmt = $db->prepare($sql);

        $permissions = isset($data['permissions']) ? json_encode($data['permissions']) : null;
        $is_super_admin = isset($data['is_super_admin']) ? (int)$data['is_super_admin'] : 0;

        $stmt->execute([
            ':username' => $data['username'],
            ':password_hash' => $data['password_hash'],
            ':email' => $data['email'],
            ':name' => $data['name'] ?? $data['username'],
            ':role' => $data['role'] ?? 'admin',
            ':is_super_admin' => $is_super_admin,
            ':permissions' => $permissions,
            ':status' => $data['status'] ?? 'active'
        ]);
        return $db->lastInsertId();
    }

    /**
     * Update an admin user.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, array $data)
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['username'])) {
            $fields[] = "username = :username";
            $params[':username'] = $data['username'];
        }
        if (isset($data['password_hash'])) {
            $fields[] = "password_hash = :password_hash";
            $params[':password_hash'] = $data['password_hash'];
        }
        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        if (isset($data['name'])) {
            $fields[] = "name = :name";
            $params[':name'] = $data['name'];
        }
        if (isset($data['role'])) {
            $fields[] = "role = :role";
            $params[':role'] = $data['role'];
        }
        if (isset($data['status'])) {
            $fields[] = "status = :status";
            $params[':status'] = $data['status'];
        }
        if (array_key_exists('is_super_admin', $data)) {
            $fields[] = "is_super_admin = :is_super_admin";
            $params[':is_super_admin'] = (int)$data['is_super_admin'];
        }
        if (array_key_exists('permissions', $data)) {
            $fields[] = "permissions = :permissions";
            $params[':permissions'] = json_encode($data['permissions']);
        }

        if (empty($fields)) {
            return true;
        }

        $sql = "UPDATE admins SET " . implode(', ', $fields) . " WHERE id = :id";
        return Database::query($sql, $params);
    }

    /**
     * Delete an admin.
     *
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        return Database::query("DELETE FROM admins WHERE id = :id", ['id' => $id]);
    }

    /**
     * Check if the admin is a super admin.
     *
     * @param array|object $admin
     * @return bool
     */
    public static function isSuperAdmin($admin)
    {
        if (is_object($admin)) {
            return (bool)($admin->is_super_admin ?? false);
        }
        return (bool)($admin['is_super_admin'] ?? false);
    }

    /**
     * Check if the admin has a specific permission.
     *
     * @param array|object $admin
     * @param string $permission
     * @return bool
     */
    public static function hasPermission($admin, $permission)
    {
        if (self::isSuperAdmin($admin)) {
            return true;
        }

        $permissionsJson = is_object($admin) ? ($admin->permissions ?? null) : ($admin['permissions'] ?? null);

        $perms = json_decode($permissionsJson, true);
        if (!is_array($perms)) {
            return false;
        }

        return in_array($permission, $perms);
    }
}
