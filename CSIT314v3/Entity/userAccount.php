<?php
declare(strict_types=1);

namespace App\Entity;

use App\Config\Database;
use PDO;
use PDOException;

/**
 * Entity class representing user accounts.
 * Responsible for all database interactions related to users.
 */
final class userAccount
{
    public string $profileType;
    public string $name;
    public string $passwordHash;

    public function __construct(string $profileType, string $name, string $passwordHash)
    {
        $this->profileType   = $profileType;
        $this->name          = $name;
        $this->passwordHash  = $passwordHash;
    }

    /* ================================================================
       🔗 DATABASE CONNECTION (Shared)
    ================================================================= */
    private static function db(): PDO
    {
        return Database::getConnection(); // ✅ Shared DB connection
    }

    /* ================================================================
       ACCOUNT CREATION
    ================================================================= */

    /** Checks whether a user already exists by name. */
    public static function getUserByName(string $name): ?array
    {
        try {
            $stmt = self::db()->prepare("SELECT * FROM users WHERE name = :name LIMIT 1");
            $stmt->execute([':name' => $name]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (PDOException $e) {
            error_log('Database error in getUserByName: ' . $e->getMessage());
            return null;
        }
    }

    /** Checks if profile type exists in user_profiles table */
    public static function isValidProfileType(string $profileType): bool
    {
        try {
            $stmt = self::db()->prepare("
                SELECT COUNT(*) FROM user_profiles 
                WHERE profile_type = :profile_type AND status = 'active'
            ");
            $stmt->execute([':profile_type' => $profileType]);
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Database error in isValidProfileType: ' . $e->getMessage());
            return false;
        }
    }

    /** Inserts a new user account */
    public static function createAccount(string $name, string $passwordHash, string $profile_type): bool
    {
        try {
            $stmt = self::db()->prepare("
                INSERT INTO users (name, password_hash, profile_type, status, created_at)
                VALUES (:name, :password_hash, :profile_type, 'active', NOW())
            ");
            return $stmt->execute([
                ':name'          => $name,
                ':password_hash' => $passwordHash,
                ':profile_type'  => $profile_type
            ]);
        } catch (PDOException $e) {
            error_log('Database Error in createAccount: ' . $e->getMessage());
            return false;
        }
    }

    /* ================================================================
       USER RETRIEVAL (LIST, VIEW, SEARCH)
    ================================================================= */

    public static function getAllUsers(): array
    {
        try {
            $stmt = self::db()->query("SELECT id, name, profile_type, created_at, status FROM users ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Error fetching users: ' . $e->getMessage());
            return [];
        }
    }

    public static function getUserById(int $id): ?array
    {
        try {
            $stmt = self::db()->prepare("SELECT id, name, profile_type, created_at, status FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            return $row ?: null;
        } catch (PDOException $e) {
            error_log('Error fetching user by ID: ' . $e->getMessage());
            return null;
        }
    }

    public static function searchUsers(string $term): array
    {
        try {
            $stmt = self::db()->prepare("
                SELECT id, name, profile_type, created_at, status
                FROM users
                WHERE name LIKE :term
                   OR profile_type LIKE :term
                   OR status LIKE :term
                ORDER BY id ASC
            ");
            $stmt->execute([':term' => "%{$term}%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error searching users: ' . $e->getMessage());
            return [];
        }
    }

    /* ================================================================
       USER UPDATE
    ================================================================= */

    public static function updateUser(int $id, string $name, string $profileType): bool
    {
        try {
            $stmt = self::db()->prepare("
                UPDATE users
                SET name = :name, profile_type = :profile_type
                WHERE id = :id
            ");
            $stmt->execute([
                ':name' => $name,
                ':profile_type' => $profileType,
                ':id' => $id
            ]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Database error in updateUser: ' . $e->getMessage());
            return false;
        }
    }

    /* ================================================================
       SUSPEND ACCOUNT
    ================================================================= */

    public static function suspendUser(int $id, bool $suspend_acc): bool
    {
        $desired = $suspend_acc ? 'suspended' : 'active';

        $cur = self::getUserById($id);
        if (!$cur) return false;

        $current = strtolower((string)($cur['status'] ?? ''));
        if ($current === $desired) return true;

        $stmt = self::db()->prepare("UPDATE users SET status = :status WHERE id = :id");
        $ok = $stmt->execute([':status' => $desired, ':id' => $id]);
        if (!$ok) return false;

        if ($stmt->rowCount() === 0) {
            $again = self::getUserById($id);
            return $again && strtolower((string)$again['status']) === $desired;
        }

        return true;
    }

    public static function findByName(string $name): ?array
    {
        $stmt = self::db()->prepare("
            SELECT id, name, password_hash, profile_type, status
            FROM users
            WHERE name = :name
            LIMIT 1
        ");
        $stmt->execute([':name' => $name]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /* ================================================================
       USER DELETION
    ================================================================= */

    public static function deleteUser(int $id): bool
    {
        try {
            $stmt = self::db()->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Error deleting user: ' . $e->getMessage());
            return false;
        }
    }

    public static function getAllProfileTypes(): array
    {
        try {
            $stmt = self::db()->query("
                SELECT profile_type 
                FROM user_profiles 
                WHERE status = 'active' 
                ORDER BY profile_type ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Database error in getAllProfileTypes: ' . $e->getMessage());
            return [];
        }
    }
}
