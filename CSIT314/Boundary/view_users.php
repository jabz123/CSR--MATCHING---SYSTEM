<?php
declare(strict_types=1);

session_start();

// Only allow admins
if (empty($_SESSION['profile_type']) || $_SESSION['profile_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$pdo = new PDO("mysql:host=127.0.0.1;dbname=csit314;charset=utf8mb4", "root", "");
$stmt = $pdo->query("SELECT id, name, profile_type, created_at FROM users ORDER BY id ASC");
$users = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>User Accounts</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { max-width: 700px; margin: 40px auto; font-family: Arial, sans-serif; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    th { background: #f1f5f9; }
    a { color: #0ea5e9; text-decoration: none; }
  </style>
</head>
<body>
  <h2>User Accounts</h2>
  <p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?> (<?= htmlspecialchars($_SESSION['profile_type']) ?>)</p>
  <a href="logout.php">Logout</a>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Profile Type</th>
      <th>Created At</th>
    </tr>
    <?php foreach ($users as $u): ?>
  <tr>
    <td><?= htmlspecialchars((string)($u['id'] ?? '')) ?></td>
    <td><?= htmlspecialchars((string)($u['name'] ?? '')) ?></td>
    <td><?= htmlspecialchars((string)($u['profile_type'] ?? '')) ?></td>
    <td><?= htmlspecialchars((string)($u['created_at'] ?? '')) ?></td>
  </tr>
<?php endforeach; ?>

  </table>
</body>
</html>
