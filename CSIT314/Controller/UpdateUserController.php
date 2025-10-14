<?php
declare(strict_types=1);

namespace App\Controller;

use PDO;
use PDOException;

final class UpdateUserController
{
    private function getConnection(): PDO
    {
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

    /**
     * ✅ 获取单个用户（by ID）
     */
    public function getUserById(int $id): ?array
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            return $user ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return null;
        }
    }

    /**
     * ✅ 更新用户（角色、名字、密码）
     */
    public function updateUser(int $id, string $profileType, string $name, string $password): bool
{
    try {
        $pdo = $this->getConnection();

        if (trim($password) === '') {
            $sql = "UPDATE users SET profile_type = ?, name = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            error_log("Executing: $sql with [$profileType, $name, $id]");
            return $stmt->execute([$profileType, $name, $id]);
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET profile_type = ?, name = ?, password_hash = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            error_log("Executing: $sql with [$profileType, $name, (password hashed), $id]");
            return $stmt->execute([$profileType, $name, $passwordHash, $id]);
        }
    } catch (PDOException $e) {
        error_log("Error updating user: " . $e->getMessage());
        return false;
    }
}

}
