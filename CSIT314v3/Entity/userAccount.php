<?php
declare(strict_types=1);

namespace App\Entity;

use PDO;
use PDOException;

/**
 * Entity class representing user accounts.
 * Responsible for all database interactions related to users.
 */
final class userAccount {
    public string $profileType;
    public string $name;
    public string $passwordHash;

    public function __construct(string $profileType, string $name, string $passwordHash) {
        $this->profileType = $profileType;
        $this->name = $name;
        $this->passwordHash = $passwordHash;
    }

    /* ================================================================
       DATABASE CONNECTION
    ================================================================= */

    /**
     * Establishes and returns a PDO connection.
     * Handles connection errors gracefully.
     */
    private static function getConnection(): PDO {
        try {
            $host = '127.0.0.1';
            $db   = 'csit314';
            $user = 'root';
            $pass = '';
            $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw new PDOException('Could not connect to database.');
        }
    }

    /* ================================================================
       ACCOUNT CREATION
    ================================================================= */

    /**
     * Checks whether a user already exists by name.
     */
    public static function getUserByName(string $name): ?array
{
    try {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $name]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: null;
    } catch (\PDOException $e) {
        error_log('Database error in getUserByName: ' . $e->getMessage());
        return null;
    }
}


    /**
     * Inserts a new user account into the database.
     */
     public static function isValidProfileType(string $profileType): bool
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
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
    public static function createAccount(string $name, string $passwordHash, string $profile_type): bool
{
    try {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO users (name, password_hash, profile_type, status, created_at)
            VALUES (:name, :password_hash, :profile_type, 'active', NOW())
        ");
        return $stmt->execute([
            ':name'          => $name,
            ':password_hash' => $passwordHash,   // ← use the hash passed in
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

    /**
     * Retrieves all user accounts from the database.
     */
    public static function getAllUsers(): array {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("SELECT id, name, profile_type, created_at, status FROM users ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Error fetching users: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Retrieves a single user account by ID.
     */
    public static function getUserById(int $id): ?array {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("SELECT id, name, profile_type, created_at, status FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();
            return $user ?: null;
        } catch (PDOException $e) {
            error_log('Error fetching user by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Searches users by name or profile type.
     */
    public static function searchUsers(string $term): array {
    try {
        $pdo = self::getConnection();

        $query = "
            SELECT id, name, profile_type, created_at, status
            FROM users
            WHERE name LIKE :term
               OR profile_type LIKE :term
               OR status LIKE :term
            ORDER BY id ASC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([':term' => "%{$term}%"]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    } catch (\PDOException $e) {
        error_log('Error searching users: ' . $e->getMessage());
        return [];
    }
}


    /* ================================================================
       USER UPDATE
    ================================================================= */

    /**
     * Updates user details by ID.
     */
    public static function updateUser(int $id, string $name, string $profileType): bool {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
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
            throw new PDOException('Error updating user.');
        }
    }

     /* ================================================================
       Suspend Account
    ================================================================= */

    public static function suspendUser(int $id, bool $suspend_acc): bool
{
    // keep status values consistent with createAccount() → 'active'
    $desired = $suspend_acc ? 'suspended' : 'active';

    $pdo = self::getConnection();

    $cur = self::getUserById($id);
    if (!$cur) return false;

    $current = strtolower((string)($cur['status'] ?? ''));
    if ($current === $desired) return true; // already correct

    $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
    $ok = $stmt->execute([':status' => $desired, ':id' => $id]);
    if (!$ok) return false;

    // some PDO drivers may report rowCount()=0 if value is unchanged
    if ($stmt->rowCount() === 0) {
        $again = self::getUserById($id);
        return $again && strtolower((string)$again['status']) === $desired;
    }

    return true;
}


    // Add this helper (uses your existing getConnection())
    public static function findByName(string $name): ?array
    {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare(
            "SELECT id, name, password_hash, profile_type, status
            FROM users
            WHERE name = :name
            LIMIT 1"
        );
        $stmt->execute([':name' => $name]);
        $row = $stmt->fetch();
        return $row ?: null;
    }



    /* ================================================================
       USER DELETION
    ================================================================= */

    /**
     * Deletes a user by ID.
     */
    public static function deleteUser(int $id): bool {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Error deleting user: ' . $e->getMessage());
            throw new PDOException('Error deleting user.');
        }
    }
    public static function getAllProfileTypes(): array {
    try {
        $pdo = self::getConnection();
        $stmt = $pdo->query("
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

    /* ================================================================
       AUTHENTICATION SUPPORT (OPTIONAL)
    ================================================================= */

    /**
     * Verifies user credentials for login.
     */

}
