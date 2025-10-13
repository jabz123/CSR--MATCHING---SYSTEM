<?php
declare(strict_types=1);

namespace App\Controller;

use PDO;
use PDOException;

final class view_usersController
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
     * Fetch all users
     */
    public function getAllUsers(): array
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->query('SELECT id, name, profile_type, created_at FROM users ORDER BY id ASC');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Error fetching users: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch a specific user by ID
     */
    public function viewUserDetails(int $id): ?array
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare('SELECT id, name, profile_type, created_at FROM users WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();

            return $user ?: null;
        } catch (PDOException $e) {
            error_log('Error fetching user details: ' . $e->getMessage());
            return null;
        }
    }
}
