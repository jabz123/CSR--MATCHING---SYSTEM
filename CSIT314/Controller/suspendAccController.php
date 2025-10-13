<?php
declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);
session_start();

require_once '../Entity/userAccount.php';

class SuspendUserController {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=csit314;charset=utf8mb4", "root", "");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /** Fetch all users */
    public function getAllUsers(): array {
        $stmt = $this->pdo->query("SELECT id, name, profile_type, status, created_at FROM accountDetails ORDER BY id ASC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($rows as $row) {
            $users[] = new User($row);
        }
        return $users;
    }

    /** Suspend user by ID */
    public function suspendUser(int $id): bool {
        $stmt = $this->pdo->prepare("UPDATE accountDetails SET status = 'suspended' WHERE id = :id AND status != 'suspended'");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}  // ✅ properly closes the class before POST handler


// --- Handle POST requests ---
$controller = new SuspendUserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($id <= 0) {
        $_SESSION['flash'] = "⚠️ Invalid user ID.";
    } else {
        if ($action === 'suspend') {
            $controller->suspendUser($id);
            $_SESSION['flash'] = "✅ User #$id suspended successfully.";
        } else {
            $_SESSION['flash'] = "⚠️ Unknown action.";
        }
    }

    // ✅ Always redirect back after processing
    header("Location: ../Boundary/view_users.php");
    exit;
}