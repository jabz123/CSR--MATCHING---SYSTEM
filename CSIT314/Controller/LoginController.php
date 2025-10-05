<?php
declare(strict_types=1);

namespace App\Controller;

use PDO;
use PDOException;

final class LoginController {
    private array $errors = [];

    private function getConnection(): PDO {
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

    public function getErrors(): array {
        return $this->errors;
    }

    public function authenticate(string $name, string $password): ?array {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE name = :name LIMIT 1');
            $stmt->execute([':name' => $name]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($password, $user['password_hash'])) {
                $this->errors[] = 'Invalid name or password.';
                return null;
            }

            return $user; // return full user record
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            $this->errors[] = 'Database error. Please try again later.';
            return null;
        }
    }
}
