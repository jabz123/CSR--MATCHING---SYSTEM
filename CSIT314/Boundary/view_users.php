<?php
declare(strict_types=1);

use App\Controller\view_usersController;

require_once __DIR__ . '/../Controller/view_usersController.php';

session_start();

// Simple authentication check
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

class view_users
{
    private view_usersController $controller;

    public function __construct()
    {
        $this->controller = new view_usersController();
    }

    /**
     * Display all users in a table
     */
    public function displayUserView(): void
    {
        $users = $this->controller->getAllUsers();

        echo "<h2>All Users</h2>";

        if (empty($users)) {
            echo "<div class='empty-state'>";
            echo "<div class='empty-icon'>👥</div>";
            echo "<p>No users found.</p>";
            echo "</div>";
            return;
        }

        echo "<div class='table-container'>";
        echo "<table>";
        echo "<thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Profile Type</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>";

        foreach ($users as $user) {
            $id = htmlspecialchars((string) $user['id']);
            $name = htmlspecialchars($user['name']);
            $profile = htmlspecialchars($user['profile_type']);
            $created = htmlspecialchars($user['created_at']);

            echo "<tr>
                    <td>{$id}</td>
                    <td><strong>{$name}</strong></td>
                    <td><span class='badge badge-{$profile}'>{$profile}</span></td>
                    <td>{$created}</td>
                    <td><a href='?id={$id}' class='action-link'>View Details</a></td>
                  </tr>";
        }

        echo "</tbody></table>";
        echo "</div>";
    }

    /**
     * Display details of a specific user
     */
    public function displayUserDetail(int $id): void
    {
        $user = $this->controller->viewUserDetails($id);

        if (!$user) {
            echo "<div class='card'>";
            echo "<div class='empty-state'>";
            echo "<div class='empty-icon'>❌</div>";
            echo "<p>User not found.</p>";
            echo "<a href='view_users.php' class='btn btn-secondary'>Back to List</a>";
            echo "</div>";
            echo "</div>";
            return;
        }

        echo "<h2>User Details</h2>";
        echo "<div class='card detail-card'>";
        echo "<div class='detail-row'>";
        echo "<span class='detail-label'>ID:</span>";
        echo "<span class='detail-value'>" . htmlspecialchars((string) $user['id']) . "</span>";
        echo "</div>";
        
        echo "<div class='detail-row'>";
        echo "<span class='detail-label'>Name:</span>";
        echo "<span class='detail-value'><strong>" . htmlspecialchars($user['name']) . "</strong></span>";
        echo "</div>";
        
        echo "<div class='detail-row'>";
        echo "<span class='detail-label'>Profile Type:</span>";
        echo "<span class='detail-value'><span class='badge badge-" . htmlspecialchars($user['profile_type']) . "'>" . htmlspecialchars($user['profile_type']) . "</span></span>";
        echo "</div>";
        
        echo "<div class='detail-row'>";
        echo "<span class='detail-label'>Created At:</span>";
        echo "<span class='detail-value'>" . htmlspecialchars($user['created_at']) . "</span>";
        echo "</div>";
        
        echo "<div class='detail-actions'>";
        echo "<a href='view_users.php' class='btn btn-primary'>Back to List</a>";
        echo "</div>";
        echo "</div>";
    }
}

// --- Page Routing ---
$view = new view_users();
$userName = htmlspecialchars($_SESSION['name'] ?? 'User');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management</title>
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
      animation: fadeInDown 0.6s ease;
    }
    
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
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
      background-clip: text;
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
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    h2 {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 25px;
      color: white;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      margin-bottom: 20px;
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
    
    tbody tr:hover {
      background: #f9fafb;
    }
    
    tbody tr:last-child {
      border-bottom: none;
    }
    
    tbody td {
      padding: 16px 20px;
      color: #374151;
    }
    
    .badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .badge-admin {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
    }
    
    .badge-csr {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
    }
    
    .badge-pin {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }
    
    .badge-platform {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
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
    
    .detail-card {
      max-width: 600px;
    }
    
    .detail-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 0;
      border-bottom: 1px solid #e5e7eb;
    }
    
    .detail-row:last-of-type {
      border-bottom: none;
    }
    
    .detail-label {
      font-weight: 600;
      color: #6b7280;
      font-size: 0.9rem;
    }
    
    .detail-value {
      color: #111827;
      font-size: 1rem;
    }
    
    .detail-actions {
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid #e5e7eb;
    }
    
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #6b7280;
    }
    
    .empty-icon {
      font-size: 4rem;
      margin-bottom: 20px;
      opacity: 0.5;
    }
    
    .empty-state p {
      font-size: 1.1rem;
      margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
      .header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }
      
      .user-info {
        flex-direction: column;
      }
      
      table {
        font-size: 0.9rem;
      }
      
      thead tr th, tbody td {
        padding: 12px 10px;
      }
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="header-left">
      <div class="logo">👥</div>
      <div class="header-title">User Management</div>
    </div>
    <div class="user-info">
      <span class="welcome-text">Welcome, <strong><?= $userName ?></strong></span>
      <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>
  </div>

  <div class="container">
    <?php
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $view->displayUserDetail((int)$_GET['id']);
    } else {
        $view->displayUserView();
    }
    ?>
  </div>
</body>
</html>