<?php
declare(strict_types=1);

namespace App\Entity;

use PDO;
use PDOException;

final class userAccount {
    public string $profileType;
    public string $name;
    public string $passwordHash;

    public function __construct(string $profileType, string $name, string $passwordHash) {
        $this->profileType = $profileType;
        $this->name = $name;
        $this->passwordHash = $passwordHash;
    }

    /**
     * Establishes and returns a PDO database connection
     * Handles database connection errors
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
            throw new PDOException('Could not connect to database');
        }
    }

    /**
     * Executes database query to check if user exists
     * Handles database errors
     */
    public static function checkUserExists(string $name): bool {
        try {
            $pdo = self::getConnection();
            $check = $pdo->prepare("SELECT id FROM users WHERE name = :name LIMIT 1");
            $check->execute([':name' => $name]);
            return (bool) $check->fetch();
        } catch (PDOException $e) {
            error_log('Database error in checkUserExists: ' . $e->getMessage());
            throw new PDOException('Error checking user existence');
        }
    }

    /**
     * Executes database query to insert user
     * Handles database errors
     */
    public static function insertUser(string $name, string $passwordHash, string $profileType): bool {
        try {
            $pdo = self::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO users (name, password_hash, profile_type, created_at)
                VALUES (:name, :password_hash, :profile_type, NOW())
            ");
            $stmt->execute([
                ':name' => $name,
                ':password_hash' => $passwordHash,
                ':profile_type' => $profileType
            ]);
            return true;
        } catch (PDOException $e) {
            error_log('Database error in insertUser: ' . $e->getMessage());
            throw new PDOException('Error inserting user');
        }
    }
}