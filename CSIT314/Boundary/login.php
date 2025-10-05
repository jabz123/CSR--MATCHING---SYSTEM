<?php
declare(strict_types=1);

use App\Controller\LoginController;
require_once dirname(__DIR__) . '/Controller/LoginController.php';

session_start();

$controller = new LoginController();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if ($user = $controller->authenticate($name, $password)) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['profile_type'] = $user['profile_type'];
        $_SESSION['name'] = $user['name'];

        // Redirect to admin dashboard or other page
        header("Location: view_users.php");
        exit;
    } else {
        $errors = $controller->getErrors();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
    body { max-width: 400px; margin: 40px auto; padding: 0 16px; }
    form { display: grid; gap: 12px; }
    input { padding: 10px; border: 1px solid #ddd; border-radius: 8px; width: 100%; }
    button, .link-btn {
      padding: 12px; border: 0; border-radius: 10px;
      background: #0ea5e9; color: #fff; font-weight: 600;
      cursor: pointer; text-align: center; text-decoration: none;
    }
    .msg { padding: 10px; border-radius: 8px; background: #fee2e2; color: #b91c1c; margin-bottom: 12px; }
    .alt-actions { text-align: center; margin-top: 10px; }
  </style>
</head>
<body>
  <h2>Login</h2>

  <?php if (!empty($errors)): ?>
    <div class="msg">
      <?php foreach ($errors as $e): ?>
        <div><?= htmlspecialchars($e) ?></div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="post">
    <label>Name</label>
    <input name="name" required placeholder="Enter your name">

    <label>Password</label>
    <input type="password" name="password" required placeholder="Enter your password">

    <button type="submit">Sign In</button>
  </form>

  <div class="alt-actions">
    <p>Don’t have an account?</p>
    <a class="link-btn" href="create_account.php">Create Account</a>
  </div>
</body>
</html>

