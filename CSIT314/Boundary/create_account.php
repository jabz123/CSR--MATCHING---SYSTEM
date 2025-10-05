<?php
declare(strict_types=1);

use App\Controller\CreateAccountController;
require_once dirname(__DIR__) . '/Controller/CreateAccountController.php';

$controller = new CreateAccountController();
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $profileType = $_POST['profile_type'] ?? '';

    if ($controller->createAccount($name, $password, $profileType)) {
        $success = 'Account created successfully!';
    } else {
        $errors = $controller->getErrors();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create User Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background: #fff;
      max-width: 500px;
      margin: 40px auto;
      padding: 0 16px;
      line-height: 1.5;
    }
    h2 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }
    form {
      display: grid;
      gap: 14px;
    }
    input, select {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      width: 100%;
      font-size: 1rem;
    }
    button {
      padding: 12px;
      border: none;
      border-radius: 10px;
      background: #0ea5e9;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      font-size: 1rem;
    }
    button:hover {
      background: #0284c7;
    }
    .msg {
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    .error {
      background: #fee2e2;
      color: #b91c1c;
    }
    .success {
      background: #dcfce7;
      color: #166534;
    }
    .alt-actions {
      text-align: center;
      margin-top: 15px;
    }
    .link-btn {
      display: inline-block;
      padding: 12px;
      border: none;
      border-radius: 10px;
      background: #0ea5e9;
      color: #fff;
      font-weight: 600;
      text-decoration: none;
      text-align: center;
      width: 100%;
    }
    .link-btn:hover {
      background: #0284c7;
    }
  </style>
</head>
<body>

  <h2>Create User Account</h2>

  <?php if (!empty($errors)): ?>
    <div class="msg error">
      <?php foreach ($errors as $e): ?>
        <div><?= htmlspecialchars($e) ?></div>
      <?php endforeach; ?>
    </div>
  <?php elseif ($success): ?>
    <div class="msg success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="post">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" placeholder="e.g., John Doe" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="At least 8 characters" required>

    <label for="profile_type">User Profile Type</label>
    <select id="profile_type" name="profile_type" required>
      <option value="">-- Select Profile Type --</option>
      <option value="admin">User Admin</option>
      <option value="csr">CSR Rep</option>
      <option value="pin">PIN (Person In Need)</option>
      <option value="platform">Platform Management</option>
    </select>

    <button type="submit">Create Account</button>
  </form>

  <div class="alt-actions">
    <p>Already have an account?</p>
    <a class="link-btn" href="login.php">Back to Login</a>
  </div>

</body>
</html>
