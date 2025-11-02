<?php
declare(strict_types=1);

namespace App\Entity;

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

    /** Database connection */
    private static function getConnection(): PDO
    {
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
            error_log('DB connection error (Profile): ' . $e->getMessage());
            throw new PDOException('Database connection failed');
        }
    }

    /** Fetch all profiles */
    public static function getAllProfiles(): array
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->query("SELECT * FROM user_profiles ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Error fetching profiles: ' . $e->getMessage());
            return [];
        }
    }

    /** Get single profile */
    public static function getProfileById(int $id): ?array
    {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();
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
    } catch (\PDOException $e) {
        error_log('Error updating profile status: ' . $e->getMessage());
        return false;
    }
}

    /** Update both profile type and status */
    public static function updateProfile(int $id, string $profile_type, string $status): bool
        {
            try {
                $pdo = self::getConnection();
                $stmt = $pdo->prepare("
                    UPDATE user_profiles 
                    SET profile_type = :profile_type, 
                        status = :status 
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

    /** 
     * Fetch all active profiles 
     * (used for account creation dropdown)
     */
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
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Error fetching active profiles: ' . $e->getMessage());
            return [];
        }
    }
public function createProfile(string $type, string $status): bool
{
    try {
        $pdo = new \PDO("mysql:host=localhost;dbname=csit314", "root", "");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("
            INSERT INTO user_profiles (profile_type, status)
            VALUES (:profile_type, :status)
        ");
        $stmt->execute([
            ':profile_type' => $type,
            ':status' => $status
        ]);

        return true;
    } catch (\PDOException $e) {
        error_log('Create Profile Error: ' . $e->getMessage());
        return false;
    }
}




}

    
    

