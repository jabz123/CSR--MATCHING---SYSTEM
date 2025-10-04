<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Account;
use PDO;
use PDOException;

final class CreateAccountController {
    private array $errors = [];
    private string $successMessage = '';

   private function getConnection(): PDO {
    $host = '127.0.0.1';
    $db   = 'csit314';   // ✅ use your real DB
    $user = 'root';
    $pass = '';          // or your MySQL password
    $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

    return new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

    public function getErrors(): array {
        return $this->errors;
    }

    public function getSuccessMessage(): string {
        return $this->successMessage;
    }

    private function validate(string $profileType, string $name, string $password): bool {
        $this->errors = [];
        $validProfiles = ['admin','csr','pin','platform'];

        if (!in_array($profileType, $validProfiles, true)) {
            $this->errors[] = 'Please select a valid profile type.';
        }

        if ($name === '' || mb_strlen($name) > 80) {
            $this->errors[] = 'Name is required and must be ≤ 80 characters.';
        }

        if (mb_strlen($password) < 8) {
            $this->errors[] = 'Password must be at least 8 characters.';
        }

        return empty($this->errors);
    }

    public function createAccount(string $profileType, string $name, string $password): bool {
        if (!$this->validate($profileType, $name, $password)) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $account = new Account($profileType, $name, $passwordHash);

        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare(
                'INSERT INTO users (profile_type, name, password_hash, created_at)
                 VALUES (:profile, :name, :ph, NOW())'
            );
            $stmt->execute([
                ':profile' => $account->profileType,
                ':name'    => $account->name,
                ':ph'      => $account->passwordHash,
            ]);

            $this->successMessage = 'Account created successfully.';
            return true;
       } catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
    }
}
