<?php
declare(strict_types=1);

use App\Controller\UpdateUserController;
require_once __DIR__ . '/../Controller/UpdateUserController.php';

session_start();

/* ============================================================
   ✅ Access Control
============================================================ */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$controller = new UpdateUserController();

/* ============================================================
   ✅ Step 1: Validate User ID
============================================================ */
function validateUserId($idParam, array &$errors): int {
    $id = (int) $idParam;
    if ($id <= 0) {
        $errors[] = 'Invalid user ID.';
    }
    return $id;
}

/* ============================================================
   ✅ Step 2: Load User
============================================================ */
function loadUser(UpdateUserController $controller, int $id, array &$errors): ?array {
    $user = $controller->getUser($id);
    if (!$user) {
        $errors[] = 'User not found.';
        return null;
    }
    return $user;
}

/* ============================================================
   ✅ Step 3: Process Form Submission
============================================================ */
function processForm(UpdateUserController $controller, int $id): array {
    $errors = [];
    $success = '';

    $name = trim($_POST['name'] ?? '');
    $profileType = strtolower(trim($_POST['profile_type'] ?? ''));

    [$errors, $success] = $controller->updateUser($id, $name, $profileType);

    return [$errors, $success];
}

/* ============================================================
   ✅ MAIN PAGE FLOW (Procedural)
============================================================ */
$errors = [];
$success = '';

$id = validateUserId($_GET['id'] ?? null, $errors);

$user = null;
if (!$errors) {
    $user = loadUser($controller, $id, $errors);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$errors) {
    [$errors, $success] = processForm($controller, $id);
    $user = $controller->getUser($id); // refresh data
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update User Account</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      display: flex; justify-content: center; align-items: center;
      min-height: 100vh;
      background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
      padding: 20px;
    }
    .container {
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(0,0,0,.25);
      max-width: 550px;
      width: 100%;
      padding: 40px;
    }
    h2 {
      text-align: center;
      font-size: 1.8rem;
      background: linear-gradient(135deg,#667eea,#764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 15px;
    }
    .msg { padding: 16px; border-radius: 12px; margin-bottom: 25px; }
    .error { background:#fee2e2; color:#b91c1c; border-left:4px solid #ef4444; }
    .success { background:#dcfce7; color:#166534; border-left:4px solid #22c55e; }
    label { font-weight:600; display:block; margin-bottom:6px; color:#333; }
    input, select {
      width:100%; padding:14px; border:2px solid #ddd; border-radius:12px;
      margin-bottom:20px; font-size:1rem; transition:0.3s;
    }
    input:focus, select:focus {
      outline:none; border-color:#667eea; box-shadow:0 0 0 4px rgba(102,126,234,0.2);
    }
    button {
      width:100%; padding:14px; border:none; border-radius:12px;
      background:linear-gradient(135deg,#667eea,#764ba2);
      color:white; font-size:1.1rem; font-weight:700; cursor:pointer;
      transition:0.3s;
    }
    button:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(102,126,234,0.4); }
    .alt-actions { text-align:center; margin-top:25px; }
    .link-btn {
      display:inline-block; padding:12px 22px; border:2px solid #667eea;
      border-radius:12px; color:#667eea; font-weight:700;
      text-decoration:none; transition:0.3s;
    }
    .link-btn:hover { background:#667eea; color:#fff; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Update User</h2>

    <?php if ($errors): ?>
      <div class="msg error">
        <?php foreach ($errors as $e): ?>
          <div>⚠️ <?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
      </div>
    <?php elseif ($success): ?>
      <div class="msg success">✓ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($user): ?>
    <form method="post" novalidate>
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user['name'] ?? '') ?>">

      <label for="profile_type">User Profile Type</label>
      <select id="profile_type" name="profile_type" required>
        <option value="">-- Select Profile Type --</option>
        <?php
          $types = ['admin'=>'User Admin','csr'=>'CSR Rep','pin'=>'PIN (Person In Need)','platform'=>'Platform Management'];
          foreach ($types as $value => $label) {
              $selected = ($user['profile_type'] ?? '') === $value ? 'selected' : '';
              echo "<option value='{$value}' {$selected}>{$label}</option>";
          }
        ?>
      </select>

      <button type="submit">Update User</button>
    </form>
    <?php endif; ?>

    <div class="alt-actions">
      <a href="view_users.php" class="link-btn">Back to User List</a>
    </div>
  </div>
</body>
</html>
