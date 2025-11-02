<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
use App\Controller\view_usersController;
use App\Controller\SearchUserController;
use App\Controller\ViewUserDetailsController;

require_once __DIR__ . '/../Controller/view_usersController.php';
require_once __DIR__ . '/../Controller/SearchUserController.php';
require_once __DIR__ . '/../Controller/ViewUserDetailsController.php';

session_start();

// --- Authentication ---
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

/** Helper to safely escape output */
function esc($val): string
{
    return htmlspecialchars((string)($val ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * 🔍 Display all users in a searchable table
 */
function displayUserSearchView(): void
{
    $searchTerm = trim($_GET['q'] ?? '');
    $searchController = new SearchUserController();
    $users = $searchController->searchUsers($searchTerm);

    echo "<h2>All Users</h2>";

    // --- Search Form ---
    echo "<form method='get' class='search-form'>
            <input type='text' name='q' placeholder='Search by name or profile type...' value='" . esc($searchTerm) . "'>
            <button type='submit' class='btn btn-primary'>Search</button>
          </form>";

    if (empty($users)) {
        echo "<div class='empty-state'>
                <div class='empty-icon'>👥</div>
                <p>No users found.</p>
              </div>";
        return;
    }

    echo "<div class='table-container'>
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Profile Type</th>
                  <th>Created At</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>";

    foreach ($users as $user) {
        $id = esc($user['id']);
        $name = esc($user['name']);
        $profile = esc($user['profile_type']);
        $created = esc($user['created_at']);
        $status = esc($user['status']);

        // who is logged in (to block self-suspend)
        $currentUserId = (int)($_SESSION['user_id'] ?? 0);

        // toggle info
        $isSuspended = ($status === 'Suspended');
        $actionText  = $isSuspended ? 'Activate' : 'Suspend';
        $sParam      = $isSuspended ? 0 : 1; // 1 = suspend, 0 = activate

        // ABSOLUTE path to the action page (adjust the prefix to your project folder name)
        $baseSuspend = '/CSIT314v3/Boundary/suspendAccPage.php';

        // final URL
        $suspendUrl  = $baseSuspend . '?' . http_build_query([
          'id'     => $id,
          'action' => 'suspend',
          's'      => $sParam,
        ]);

        echo '<tr>';
        echo '  <td>'.$id.'</td>';
        echo '  <td><strong>'.$name.'</strong></td>';
        echo '  <td><span class="badge badge-'.$profile.'">'.$profile.'</span></td>';
        echo '  <td>'.$created.'</td>';
        echo '  <td>'.$status.'</td>';
        echo '  <td>';
        echo '    <a href="?id='.$id.'" class="action-link">View Details</a> ';
        echo '    <a href="update_usersPg.php?id='.$id.'" class="action-link">Update</a> ';

        if ($id === $currentUserId) {
          // don't allow an admin to suspend herself
          echo '    <span class="action-link" style="opacity:.45" title="You cannot act on yourself">—</span>';
        } else {
          echo '    <a href="'.$suspendUrl.'" class="action-link" ';
          echo '       onclick="return confirm(\'' . ($isSuspended ? 'Activate' : 'Suspend') . ' this user?\');">';
          echo          $actionText . '</a>';
        }

        echo '  </td>';
        echo '</tr>';
    }
    echo "</tbody></table></div>";
}

/**
 * 📄 Display details of a specific user
 */
function displayUserDetail(int $id): void
{
    $detailsController = new ViewUserDetailsController();
    $user = $detailsController->viewUserDetails($id);

    if (!$user) {
        echo "<div class='card'>
                <div class='empty-state'>
                  <div class='empty-icon'>❌</div>
                  <p>User not found.</p>
                  <a href='view_users.php' class='btn btn-secondary'>Back to List</a>
                </div>
              </div>";
        return;
    }

    echo "<h2>User Details</h2>
          <div class='card detail-card'>";

    echo "<div class='detail-row'><span class='detail-label'>ID:</span><span class='detail-value'>" . esc($user['id']) . "</span></div>";
    echo "<div class='detail-row'><span class='detail-label'>Name:</span><span class='detail-value'><strong>" . esc($user['name']) . "</strong></span></div>";
    echo "<div class='detail-row'><span class='detail-label'>Profile Type:</span><span class='detail-value'><span class='badge badge-" . esc($user['profile_type']) . "'>" . esc($user['profile_type']) . "</span></span></div>";
    echo "<div class='detail-row'><span class='detail-label'>Created At:</span><span class='detail-value'>" . esc($user['created_at']) . "</span></div>";
    echo "<div class='detail-row'><span class='detail-label'>Status:</span><span class='detail-value'>" . esc($user['status']) . "</span></div>";
    echo "<div class='detail-actions'><a href='view_users.php' class='btn btn-primary'>Back to List</a></div>";
    echo "</div>";
}

// --- Page Routing ---
$userName = htmlspecialchars($_SESSION['name'] ?? 'User');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /* --- Keep your existing CSS --- */
    * { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height:100vh;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
      padding:20px; position:relative; overflow-x:hidden;
    }

    /* Background Animation */
    body::before {
      content:''; position:absolute; width:100%; height:100%;
      background: radial-gradient(circle at 10% 20%, rgba(99,102,241,0.4) 0%, transparent 40%),
                  radial-gradient(circle at 90% 80%, rgba(139,92,246,0.4) 0%, transparent 40%);
      animation:pulse 8s ease-in-out infinite; z-index:0;
    }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:0.8;} }

    /* Header */
    .header { max-width:1200px; margin:0 auto 30px; background:rgba(255,255,255,0.95);
      backdrop-filter:blur(10px); border-radius:20px; padding:25px 30px; box-shadow:0 10px 40px rgba(0,0,0,0.2);
      display:flex; justify-content:space-between; align-items:center; position:relative; z-index:1;
    }

    .logo { width:50px; height:50px; background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);
      border-radius:14px; display:flex; align-items:center; justify-content:center;
      font-size:1.5rem; color:white; box-shadow:0 4px 15px rgba(99,102,241,0.4);
    }

    .header-title {
      font-size:1.5rem; font-weight:700;
      background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);
      -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    }

    .user-info { display:flex; align-items:center; gap:15px; }
    .welcome-text { color:#666; font-size:0.9rem; }

    /* Buttons */
    .btn { padding:10px 20px; border:none; border-radius:10px; font-weight:600; cursor:pointer;
      text-decoration:none; transition:all 0.3s ease; display:inline-block; font-size:0.95rem;
    }
    .btn-primary { background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%); color:white; }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 6px 16px rgba(99,102,241,0.5); }
    .btn-secondary { background:transparent; color:#6366f1; border:2px solid #6366f1; }
    .btn-secondary:hover { background:#6366f1; color:white; }
    .btn-logout { background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%); color:white; }

    /* Layout */
    .container { max-width:1200px; margin:0 auto; position:relative; z-index:1; }

    h2 { font-size:1.8rem; font-weight:700; margin-bottom:25px; color:white; text-shadow:0 2px 10px rgba(0,0,0,0.2); }

    /* Table + Search */
    .search-form { margin-bottom:25px; text-align:center; }
    .search-form input[type='text'] {
      padding:12px 18px; font-size:1rem; border:2px solid #ccc;
      border-radius:12px; width:60%; transition:0.3s;
    }
    .search-form input:focus {
      border-color:#6366f1; outline:none; box-shadow:0 0 0 4px rgba(99,102,241,0.15);
    }

    .table-container { background:rgba(255,255,255,0.95); border-radius:20px; overflow:hidden; box-shadow:0 10px 40px rgba(0,0,0,0.2); }
    table { width:100%; border-collapse:collapse; }
    thead { background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%); color:white; }
    thead th { padding:18px 20px; text-align:left; }
    tbody td { padding:16px 20px; color:#374151; border-bottom:1px solid #e5e7eb; }
    tbody tr:hover { background:#f9fafb; }

    .badge { display:inline-block; padding:6px 12px; border-radius:20px; font-size:0.85rem; font-weight:600; text-transform:uppercase; }
    .badge-admin { background:linear-gradient(135deg,#ef4444,#dc2626); color:white; }
    .badge-csr { background:linear-gradient(135deg,#3b82f6,#2563eb); color:white; }
    .badge-pin { background:linear-gradient(135deg,#10b981,#059669); color:white; }
    .badge-platform { background:linear-gradient(135deg,#f59e0b,#d97706); color:white; }

    .action-link { color:#6366f1; text-decoration:none; font-weight:600; padding:6px 12px; border-radius:8px; }
    .action-link:hover { background:#ede9fe; color:#5b21b6; }

    /* Empty State */
    .empty-state { text-align:center; padding:60px 20px; color:#6b7280; }
    .empty-icon { font-size:4rem; margin-bottom:20px; opacity:0.5; }

    @media (max-width:768px) {
      .header { flex-direction:column; text-align:center; }
      .user-info { flex-direction:column; }
      .search-form input { width:100%; margin-bottom:10px; }
      table { font-size:0.9rem; }
      thead th, tbody td { padding:12px 10px; }
    }
  </style>
</head>
<body>
  <?php
  if (isset($_GET['notice'])) {
      $id  = isset($_GET['id']) ? (int)$_GET['id'] : null;

      $map = [
          'suspended' => "User #$id suspended successfully.",
          'activated' => "User #$id activated successfully.",
          'failed'    => "Failed to update status for user #$id.",
          'invalid'   => "Invalid request.",
          'self'      => "You cannot suspend your own account.",
          'forbidden' => "Only User Admins can perform this action.",
      ];

      $msg = $map[$_GET['notice']] ?? 'Unknown operation.';

      $why = $_GET['why'] ?? null;
      if ($why) { $msg .= ' (debug: ' . htmlspecialchars($why) . ')'; }

      echo "<script>window.addEventListener('DOMContentLoaded',()=>alert(" . json_encode($msg) . "));</script>";

      $ok = in_array($_GET['notice'], ['suspended','activated'], true);
      echo '<div style="max-width:1200px;margin:0 auto 12px;background:#fff;padding:10px 14px;border-radius:10px;'
        . 'border:1px solid ' . ($ok ? '#0a0' : '#a00') . ';">' . htmlspecialchars($msg) . '</div>';
  }
  ?>
  <div class="header">
    <div class="header-left">
      <div class="logo">👥</div>
      <div class="header-title">User Management</div>
    </div>
    <div class="user-info">
      <span class="welcome-text">Welcome, <strong><?= $userName ?></strong></span>
      <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
      <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>
  </div>

  <div class="container">
    <?php
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        displayUserDetail((int)$_GET['id']);
    } else {
        displayUserSearchView();
    }
    ?>
  </div>
</body>
</html> 