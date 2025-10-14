<?php
declare(strict_types=1);

use App\Controller\UpdateUserController;
use App\Entity\userAccount;

require_once __DIR__ . '/../Controller/UpdateUserController.php';
require_once __DIR__ . '/../Entity/userAccount.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$controller = new UpdateUserController();
$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = $controller->getUserById($userId);

if (!$user) {
    echo "<h2>User not found.</h2>";
    echo "<a href='view_users.php'>Back to list</a>";
    exit;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated = $controller->updateUser($userId, $_POST['profile_type'], $_POST['name'], $_POST['password']);
    if ($updated) {
        $message = "✅ User updated successfully!";
    } else {
        $message = "❌ Failed to update user.";
    }
}
$userName = htmlspecialchars($_SESSION['name'] ?? 'User');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update User Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }
    body::before {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background:
        radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.4) 0%, transparent 40%),
        radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.4) 0%, transparent 40%);
      animation: pulse 8s ease-in-out infinite;
      z-index: 0;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.8; }
    }
    .header {
      max-width: 1200px;
      margin: 0 auto 30px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 25px 30px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
      z-index: 1;
    }
    .logo {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }
    .header-title {
      font-size: 1.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .user-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .welcome-text {
      color: #666;
      font-size: 0.9rem;
    }
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-block;
      font-size: 0.95rem;
    }
    .btn-primary {
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(99, 102, 241, 0.5);
    }
    .btn-secondary {
      background: transparent;
      color: #6366f1;
      border: 2px solid #6366f1;
    }
    .btn-secondary:hover {
      background: #6366f1;
      color: white;
      transform: translateY(-2px);
    }
    .form-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      max-width: 600px;
      margin: 0 auto;
      z-index: 1;
    }
    h2 {
      text-align: center;
      font-size: 1.8rem;
      font-weight: 700;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 30px;
    }
    label {
      display: block;
      font-weight: 600;
      margin-top: 15px;
      color: #555;
    }
    input, select {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 1px solid #ccc;
      margin-top: 5px;
      font-size: 1rem;
    }
    .message {
      text-align: center;
      font-weight: 600;
      margin-bottom: 20px;
      color: #16a34a;
      animation: fadeIn 0.5s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="header-left">
      <div class="logo">⚙️</div>
      <div class="header-title">Update User</div>
    </div>
    <div class="user-info">
      <span class="welcome-text">Welcome, <strong><?= $userName ?></strong></span>
      <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
  </div>

  <div class="form-card">
    <h2>Edit User Account</h2>
    <?php if ($message): ?>
      <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label for="name">Name:</label>
      <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

      <label for="profile_type">Role:</label>
      <select name="profile_type" required>
        <option value="admin" <?= $user['profile_type']==='admin' ? 'selected' : '' ?>>Admin</option>
        <option value="csr" <?= $user['profile_type']==='csr' ? 'selected' : '' ?>>CSR</option>
        <option value="pin" <?= $user['profile_type']==='pin' ? 'selected' : '' ?>>PIN</option>
        <option value="platform" <?= $user['profile_type']==='platform' ? 'selected' : '' ?>>Platform</option>
      </select>

      <label for="password">New Password:</label>
      <input type="password" name="password" placeholder="Leave blank to keep current password">

      <div style="text-align:center; margin-top:25px;">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="view_users.php" class="btn btn-secondary" style="margin-left:10px;">Back</a>
      </div>
    </form>
  </div>
</body>
</html>
