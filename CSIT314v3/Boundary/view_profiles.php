<?php
declare(strict_types=1);
session_start();

use App\Controller\ViewProfilesController;
use App\Controller\SearchProfileController;
use App\Controller\CreateProfileController;

require_once __DIR__ . '/../Controller/CreateProfileController.php';
require_once __DIR__ . '/../Controller/SearchProfileController.php';
require_once __DIR__ . '/../Controller/ViewProfilesController.php';

/* ------------------- Helper Functions (Boundary Only) ------------------- */

/** Check login */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/** Get logged-in username */
function getLoggedInUserName(): string {
    return htmlspecialchars($_SESSION['name'] ?? 'User');
}

/** Get trimmed search term */
function getSearchTerm(): string {
    return trim($_GET['search'] ?? '');
}

/** Load profiles list */
function loadProfilesList(ViewProfilesController $vc, SearchProfileController $sc): array {
    $term = getSearchTerm();
    return $term !== '' ? $sc->searchProfiles($term) : $vc->getAllProfiles();
}

/** Handle add profile form */
function handleAddProfileForm(CreateProfileController $cc): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_profile_type'])) {
        $newType = trim($_POST['new_profile_type']);
        $status = $_POST['status'] ?? 'active';

        if ($newType !== '') {
            if ($cc->createProfile($newType, $status)) {
                header("Location: view_profiles.php?success=1");
                exit();
            } else {
                echo "❌ Failed to create new profile.";
            }
        }
    }
}

/* ------------------- Page Access Check ------------------- */

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

/* ------------------- Controllers ------------------- */

$viewController = new ViewProfilesController();
$createController = new CreateProfileController();
$searchController = new SearchProfileController();

/* ------------------- Boundary Logic Execution ------------------- */

handleAddProfileForm($createController);
$userName = getLoggedInUserName();
$searchTerm = getSearchTerm();
$profiles = loadProfilesList($viewController, $searchController);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile Management</title>
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
      z-index: -1;
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
      animation: fadeInDown 0.6s ease;
    }
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .header-left {
      display: flex;
      align-items: center;
      gap: 15px;
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
      background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
    }
    .btn-secondary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(20, 184, 166, 0.5);
    }
    .btn-logout {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    .btn-logout:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(239, 68, 68, 0.5);
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      position: relative;
      z-index: 1;
      animation: fadeInUp 0.6s ease;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .table-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    thead {
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    }
    thead tr th {
      padding: 18px 20px;
      text-align: left;
      color: white;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    tbody tr {
      border-bottom: 1px solid #e5e7eb;
      transition: background 0.2s ease;
    }
    tbody tr:hover { background: #f9fafb; }
    tbody td {
      padding: 16px 20px;
      color: #374151;
    }
    .action-link {
      color: #6366f1;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s ease;
      padding: 6px 12px;
      border-radius: 8px;
      display: inline-block;
    }
    .action-link:hover {
      background: #ede9fe;
      color: #5b21b6;
    }
    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 10;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background: #fff;
        padding: 25px;
        border-radius: 15px;
        width: 360px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .modal-content input, .modal-content select {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }
    .modal-content button {
        background-color: #6366f1;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }
</style>
</head>
<body>
<div class="header">
    <div class="header-left">
        <div class="logo">🧩</div>
        <div class="header-title">Profile Management</div>
    </div>
    <div class="user-info">
        <span class="welcome-text">Welcome, <strong><?= $userName ?></strong></span>
        <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
        <button class="btn btn-primary" onclick="document.getElementById('addModal').style.display='flex'">+ Add New Profile</button>
        <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>
</div>
<!-- Search Bar (Identical to view_users) -->
<div class="search-bar" style="max-width:1200px;margin:20px auto 30px;display:flex;justify-content:space-between;align-items:center;gap:10px;">
    <form method="GET" action="view_profiles.php" style="flex:1;display:flex;gap:10px;">
        <input type="text" name="search" placeholder="Search by Profile Type or Status"
               value="<?= htmlspecialchars($searchTerm ?? '') ?>"
               style="flex:1;padding:10px 15px;border-radius:10px;border:1px solid #ccc;font-size:1rem;">
        <button type="submit" class="btn btn-primary"
                style="padding:10px 20px;border:none;border-radius:10px;
                       background:linear-gradient(135deg,#6366f1,#8b5cf6);
                       color:white;font-weight:600;cursor:pointer;">
            Search
        </button>
        <?php if (!empty($searchTerm)): ?>
        <a href="view_profiles.php"
           class="btn btn-secondary"
           style="text-decoration:none;padding:10px 20px;border-radius:10px;
                  background:linear-gradient(135deg,#14b8a6,#0d9488);
                  color:white;font-weight:600;">
            Clear
        </a>
        <?php endif; ?>
    </form>
</div>

<div class="container">
    <?php if (isset($_GET['success'])): ?>
        <p style="color:green;text-align:center;font-weight:500;">✅ New profile added successfully!</p>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Profile Type</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($profiles as $profile): ?>
    <tr>
        <td><?= htmlspecialchars((string)$profile['id']) ?></td>
        <td><?= htmlspecialchars($profile['profile_type']) ?></td>
        <td><?= htmlspecialchars($profile['created_at']) ?></td>
        <td>
            <span style="color:<?= $profile['status'] === 'active' ? '#16a34a' : '#dc2626'; ?>;font-weight:600;">
                <?= ucfirst(htmlspecialchars($profile['status'])) ?>
            </span>
        </td>
        <td>
            <!-- View Button -->
            <a href="view_profilePg.php?id=<?= $profile['id'] ?>"
               class="action-link"
               style="color:#2563eb;">
               View
            </a>

            <!-- Update Button -->
            <a href="update_profilePg.php?id=<?= $profile['id'] ?>"
               class="action-link"
               style="color:#10b981;">
               Update
            </a>

            <!-- Suspend or Activate -->
            <?php if ($profile['status'] === 'active'): ?>
                <a href="suspendProfilePg.php?id=<?= $profile['id'] ?>&action=suspend"
                   class="action-link"
                   style="color:#ef4444;">
                   Suspend
                </a>
            <?php else: ?>
                <a href="suspendProfilePg.php?id=<?= $profile['id'] ?>&action=activate"
                   class="action-link"
                   style="color:#10b981;">
                   Activate
                </a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>

        </table>
    </div>
</div>

<!-- Add New Profile Modal -->
<div class="modal" id="addModal">
    <div class="modal-content">
        <h3 style="margin-bottom:15px;">Add New Profile</h3>
        <form method="POST" action="">
            <label for="new_profile_type">Profile Type</label>
            <input type="text" name="new_profile_type" id="new_profile_type" placeholder="e.g. Manager" required>

            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
            </select>

            <button type="submit">Add Profile</button>
            <button type="button" onclick="document.getElementById('addModal').style.display='none'" style="background:#aaa;margin-left:10px;">Cancel</button>
        </form>
    </div>
</div>

<script>
window.onclick = function(event) {
    const modal = document.getElementById('addModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};
</script>
</body>
</html>
