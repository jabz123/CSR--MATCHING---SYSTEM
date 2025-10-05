<?php
declare(strict_types=1);

namespace App\Controller;

use PDO;
use PDOException;

final class CreateAccountController {
    private array $errors = [];

    private function getConnection(): PDO {
        $host = '127.0.0.1';
        $db   = 'csit314'; // ✅ Make sure this matches your database
        $user = 'root';    // ✅ Default for XAMPP
        $pass = '';        // ✅ Add password if you set one in MySQL
        $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function createAccount(string $name, string $password, string $profileType): bool {
        // ✅ Trim input
        $name = trim($name);
        $password = trim($password);
        $profileType = strtolower(trim($profileType));

        // ✅ Basic validation
        if ($name === '' || $password === '') {
            $this->errors[] = 'Name and password are required.';
        }

        if (strlen($password) < 8) {
            $this->errors[] = 'Password must be at least 8 characters.';
        }

        // ✅ Allowed profile types (MUST match DB enum)
        $validProfiles = ['admin', 'csr', 'pin', 'platform'];
        if (!in_array($profileType, $validProfiles, true)) {
            $this->errors[] = 'Please select a valid profile type.';
        }

        if (!empty($this->errors)) {
            return false;
        }

        // ✅ Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $pdo = $this->getConnection();

            // ✅ Prevent duplicate names
            $check = $pdo->prepare("SELECT id FROM users WHERE name = :name LIMIT 1");
            $check->execute([':name' => $name]);
            if ($check->fetch()) {
                $this->errors[] = 'A user with this name already exists.';
                return false;
            }

            // ✅ Insert user into DB
            $stmt = $pdo->prepare("
                INSERT INTO users (name, password_hash, profile_type, created_at)
                VALUES (:name, :password_hash, :profile_type, NOW())
            ");
            $stmt->execute([
                ':name' => $name,
                ':password_hash' => $hashedPassword,
                ':profile_type' => $profileType
            ]);

            return true;
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            $this->errors[] = 'An internal error occurred. Please try again later.';
            return false;
        }
    }
}
