<?php
declare(strict_types=1);
session_start();
require_once 'suspendAccController.php';

$controller = new SuspendUserController();
$users = $controller->getAllUsers();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Suspend / Activate Users</title>
  <style>
    body { max-width:900px; margin:40px auto; font-family:Arial, sans-serif; }
    table { width:100%; border-collapse:collapse; margin-top:20px; }
    th, td { border:1px solid #ddd; padding:10px; text-align:left; }
    th { background:#f1f5f9; }
    .flash { background:#e0f2fe; border:1px solid #0284c7; padding:10px; border-radius:8px; }
    .badge { padding:2px 8px; border-radius:10px; font-size:12px; }
    .active { background:#dcfce7; border:1px solid #16a34a; }
    .suspended { background:#fee2e2; border:1px solid #dc2626; }
    form { display:inline; }
    button { padding:6px 10px; border-radius:8px; cursor:pointer; }
  </style>
</head>
<body>
  <h2>User Management (Controller-based)</h2>

  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
  <?php endif; ?>

  <table>
    <tr>
      <th>ID</th><th>Name</th><th>Profile</th><th>Status</th><th>Created</th><th>Action</th>
    </tr>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= $u->id ?></td>
        <td><?= htmlspecialchars($u->name) ?></td>
        <td><?= htmlspecialchars($u->profile_type) ?></td>
        <td><span class="badge <?= htmlspecialchars($u->status) ?>"><?= htmlspecialchars($u->status) ?></span></td>
        <td><?= htmlspecialchars($u->created_at) ?></td>
        <td>
          <?php if ($u->status !== 'suspended'): ?>
            <form method="post" action="SuspendUserController.php" onsubmit="return confirm('Suspend this user?');">
              <input type="hidden" name="id" value="<?= $u->id ?>">
              <input type="hidden" name="action" value="suspend">
              <button type="submit">Suspend</button>
            </form>
          <?php else: ?>
            <form method="post" action="SuspendUserController.php" onsubmit="return confirm('Activate this user?');">
              <input type="hidden" name="id" value="<?= $u->id ?>">
              <input type="hidden" name="action" value="activate">
              <button type="submit">Activate</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
