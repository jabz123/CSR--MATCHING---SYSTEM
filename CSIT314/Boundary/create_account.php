<?php
declare(strict_types=1);

use App\Controller\CreateAccountController;
require_once dirname(__DIR__) . '/Controller/CreateAccountController.php';
require_once dirname(__DIR__) . '/Entity/Account.php';

session_start();
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if (!function_exists('h')) {
    function h(?string $s): string {
        return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

$controller = new CreateAccountController();
$errors = [];
$success = '';
$old = ['profile_type' => '', 'name' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'], $token)) {
        $errors[] = 'Invalid form token. Please refresh and try again.';
    } else {
        $profileType = $_POST['profile_type'] ?? '';
        $name        = trim($_POST['name'] ?? '');
        $password    = (string)($_POST['password'] ?? '');

        $old['profile_type'] = $profileType;
        $old['name']         = $name;

        if ($controller->createAccount($profileType, $name, $password)) {
            $success = $controller->getSuccessMessage();
            $old = ['profile_type' => '', 'name' => ''];
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        } else {
            $errors = $controller->getErrors();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Create Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; }
    body { max-width: 560px; margin: 40px auto; padding: 0 16px; }
    form { display: grid; gap: 12px; }
    label { font-weight: 600; }
    input, select { padding: 10px; border: 1px solid #ddd; border-radius: 8px; width: 100%; }
    button { padding: 12px 16px; border: 0; border-radius: 10px; font-weight: 600; cursor: pointer; }
    .primary { background: #0ea5e9; color: white; }
    .msg { padding: 12px; border-radius: 10px; margin-bottom: 12px; }
    .error { background: #fee2e2; }
    .success { background: #dcfce7; }
    .stack { display: grid; gap: 8px; }
  </style>
</head>
<body>
  <h2>Create User Account</h2>

  <?php if (!empty($errors)): ?>
    <div class="msg error">
      <div class="stack">
        <?php foreach ($errors as $e): ?>
          <div><?= h($e) ?></div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="msg success"><?= h($success) ?></div>
  <?php endif; ?>

 <form method="post" novalidate>
    <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">

    <label for="name">Name</label>
    <input id="name" name="name" required maxlength="80"
           value="<?= h($old['name']) ?>" placeholder="e.g., John Doe">

    <label for="pw">Password</label>
    <input id="pw" name="password" type="password" required minlength="8"
           placeholder="At least 8 characters">

    <label for="profile">User Profile Type</label>
    <select id="profile" name="profile_type" required>
        <option value="">-- Select Profile Type --</option>
        <option value="admin" <?= $old['profile_type']==='admin'?'selected':'' ?>>User Admin</option>
        <option value="csr" <?= $old['profile_type']==='csr'?'selected':'' ?>>CSR Rep</option>
        <option value="pin" <?= $old['profile_type']==='pin'?'selected':'' ?>>PIN (Person-in-Need)</option>
        <option value="platform" <?= $old['profile_type']==='platform'?'selected':'' ?>>Platform Management</option>
    </select>

    <button class="primary" type="submit">Create Account</button>
</form>
</body>
</html>
