<?php
declare(strict_types=1);

namespace App\Entity;

use PDO;
use PDOException;

final class userAccount {
    public string $profileType;
    public string $name;
    public string $email;
    public string $password;

    public function __construct(string $profileType, string $name, string $email, string $password) {
        $this->profileType = $profileType;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    private static function getConnection(): PDO {
        $host = '127.0.0.1';
        $db   = 'csit314';
        $user = 'root';
        $pass = '';
        $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public static function getUserById(int $id): ?array {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function update_user(int $id): bool {
        try {
            $pdo = self::getConnection();

            if (empty($this->password)) {
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET profile_type = :profile_type, 
                        name = :name,
                        email = :email
                    WHERE id = :id
                ");
                return $stmt->execute([
                    ':profile_type' => $this->profileType,
                    ':name' => $this->name,
                    ':email' => $this->email,
                    ':id' => $id
                ]);
            } else {
                $passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET profile_type = :profile_type, 
                        name = :name,
                        email = :email,
                        password_hash = :password
                    WHERE id = :id
                ");
                return $stmt->execute([
                    ':profile_type' => $this->profileType,
                    ':name' => $this->name,
                    ':email' => $this->email,
                    ':password' => $passwordHash,
                    ':id' => $id
                ]);
            }
        } catch (PDOException $e) {
            error_log("Update failed: " . $e->getMessage());
            return false;
        }
    }
}
