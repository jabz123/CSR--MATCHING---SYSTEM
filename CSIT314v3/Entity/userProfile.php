<?php
declare(strict_types=1);

namespace App\Entity;

use App\Config\Database;
use PDO;
use PDOException;

final class userProfile
{
    public int $id;
    public string $profile_type;
    public string $status;
    public string $created_at;

    public function __construct(int $id, string $profile_type, string $status, string $created_at)
    {
        $this->id = $id;
        $this->profile_type = $profile_type;
        $this->status = $status;
        $this->created_at = $created_at;
    }

    /** Reusable DB connection */
    private static function getConnection(): PDO
    {
        return Database::getConnection();
    }

    /** Fetch all profiles */
    public static function getAllProfiles(): array
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("SELECT * FROM user_profiles ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching profiles: ' . $e->getMessage());
            return [];
        }
    }

    /** Get single profile by ID */
    public static function getProfileById(int $id): ?array
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log('Error fetching profile by ID: ' . $e->getMessage());
            return null;
        }
    }

    /** Update profile type */
    public static function updateProfileType(int $id, string $profile_type): bool
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("UPDATE user_profiles SET profile_type = :profile_type WHERE id = :id");
            return $stmt->execute([':profile_type' => $profile_type, ':id' => $id]);
        } catch (PDOException $e) {
            error_log('Error updating profile type: ' . $e->getMessage());
            return false;
        }
    }

    /** Update profile status */
    public static function updateProfileStatus(int $id, string $status): bool
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("UPDATE user_profiles SET status = :status WHERE id = :id");
            return $stmt->execute([':status' => $status, ':id' => $id]);
        } catch (PDOException $e) {
            error_log('Error updating profile status: ' . $e->getMessage());
            return false;
        }
    }

    /** Update profile type + status */
    public static function updateProfile(int $id, string $profile_type, string $status): bool
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
                UPDATE user_profiles 
                SET profile_type = :profile_type, status = :status
                WHERE id = :id
            ");
            return $stmt->execute([
                ':profile_type' => $profile_type,
                ':status' => $status,
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log('Error updating profile: ' . $e->getMessage());
            return false;
        }
    }

    /** Search profiles by profile_type or status */
    public static function searchProfiles(string $searchTerm): array
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
                SELECT * FROM user_profiles
                WHERE profile_type LIKE :term OR status LIKE :term
                ORDER BY created_at DESC
            ");
            $stmt->execute([':term' => "%$searchTerm%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error searching profiles: ' . $e->getMessage());
            return [];
        }
    }

    /** Fetch all active profiles (for account creation dropdown) */
    public static function getActiveProfiles(): array
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("
                SELECT id, profile_type 
                FROM user_profiles 
                WHERE status = 'active' 
                ORDER BY profile_type ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching active profiles: ' . $e->getMessage());
            return [];
        }
    }

    /** Create a new user profile */
    public static function createProfile(string $type, string $status): bool
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO user_profiles (profile_type, status, created_at)
                VALUES (:type, :status, NOW())
            ");
            return $stmt->execute([
                ':type' => $type,
                ':status' => $status
            ]);
        } catch (PDOException $e) {
            error_log('Create Profile Error: ' . $e->getMessage());
            return false;
        }
    }
}
