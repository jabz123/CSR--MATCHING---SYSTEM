<?php
declare(strict_types=1);
session_start();

// ---- AuthZ guard: only CSR allowed ----
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['profile_type'] ?? '') !== 'csr') {
    // Redirect to appropriate dashboard
    switch ($_SESSION['profile_type'] ?? '') {
        case 'admin':
            header('Location: admin_dashboard.php');
            break;
        case 'pin':
            header('Location: pin_dashboard.php');
            break;
        case 'platform':
            header('Location: platform_dashboard.php');
            break;
        default:
            header('Location: login.php');
    }
    exit;
}

// ---- Friendly name + safe escaping ----
$userName = htmlspecialchars($_SESSION['username'] ?? $_SESSION['name'] ?? 'CSR Representative', ENT_QUOTES, 'UTF-8');

// ---- CSRF token for downstream forms ----
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// ---- Flash messages (e.g., ?success=1 or ?error=msg) ----
$success = isset($_GET['success']) ? (int) $_GET['success'] : 0;
$errorMsg = isset($_GET['error']) ? trim((string) $_GET['error']) : '';
if ($errorMsg !== '') {
    $errorMsg = htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>CSR Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

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
      inset: 0;
      background:
        radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.4) 0%, transparent 40%),
        radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.4) 0%, transparent 40%);
      animation: pulse 8s ease-in-out infinite;
      z-index: 0;
    }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.8; } }

    .header {
      max-width: 1200px;
      margin: 0 auto 30px;
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 25px 30px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2);
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative; z-index: 1;
      animation: fadeInDown 0.6s ease;
    }
    @keyframes fadeInDown { from { opacity:0; transform: translateY(-30px); } to { opacity:1; transform: translateY(0); } }

    .header-left { display: flex; align-items: center; gap: 15px; }
    .logo {
      width: 50px; height: 50px;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      border-radius: 14px; display: flex; align-items: center; justify-content: center;
      font-size: 1.5rem; color: white;
      box-shadow: 0 4px 15px rgba(99,102,241,0.4);
    }
    .header-title {
      font-size: 1.5rem; font-weight: 700;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .user-info { display: flex; align-items: center; gap: 15px; }
    .welcome-text { color: #666; font-size: 0.9rem; }

    .btn {
      padding: 10px 20px; border: none; border-radius: 10px;
      font-weight: 600; cursor: pointer; text-decoration: none;
      transition: all 0.3s ease; display:inline-block; font-size:0.95rem;
    }
    .btn-primary {
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      color: white; box-shadow: 0 4px 12px rgba(99,102,241,0.3);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(99,102,241,0.5); }

    .btn-secondary {
      background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
      color: white; box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(37,99,235,0.5); }

    .btn-accent {
      background: linear-gradient(135deg, #db2777 0%, #be185d 100%);
      color: white; box-shadow: 0 4px 12px rgba(219,39,119,0.3);
    }
    .btn-accent:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(219,39,119,0.5); }

    .btn-logout {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color:white; box-shadow: 0 4px 12px rgba(239,68,68,0.3);
    }
    .btn-logout:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(239,68,68,0.5); }

    .container {
      max-width: 1000px; margin: 0 auto;
      background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
      border-radius: 20px; padding: 40px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2);
      position: relative; z-index: 1; animation: fadeInUp 0.6s ease;
    }
    @keyframes fadeInUp { from { opacity:0; transform: translateY(40px);} to {opacity:1; transform:translateY(0);} }

    h2 { font-size: 2rem; font-weight: 700; margin-bottom: 20px; color: #4f46e5; }
    .sub { color:#666; margin-bottom: 30px; }

    .alerts { margin-bottom: 20px; }
    .alert {
      border-radius: 12px; padding: 12px 16px; font-weight:600; margin-bottom: 10px;
    }
    .alert-success { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
    .alert-error   { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }

    .grid {
      display:grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 20px;
      margin-top: 10px;
    }
    .card-full { grid-column: 1 / -1; }
    .card {
      background: white; border-radius: 16px; padding: 24px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      transition: transform .2s ease, box-shadow .2s ease;
      text-align: left;
    }
    .card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(0,0,0,0.12); }
    .card h3 { margin-bottom: 8px; color:#111827; }
    .card p { color:#6b7280; margin-bottom: 14px; }
    .card .actions { display:flex; gap: 10px; flex-wrap: wrap; }

    @media (max-width: 900px) {
      .grid { grid-template-columns: 1fr; }
      .container { padding: 28px; }
      .header { flex-direction: column; gap: 15px; }
      .user-info { flex-direction: column; }
    }
  </style>
</head>
<body>
  <div class="header" role="banner" aria-label="CSR header">
    <div class="header-left">
      <div class="logo" aria-hidden="true">🎧</div>
      <div class="header-title">CSR Dashboard</div>
    </div>
    <div class="user-info">
      <span class="welcome-text">Welcome, <strong><?php echo $userName; ?></strong></span>
      <a href="logout.php" class="btn btn-logout" title="Log out">Logout</a>
    </div>
  </div>

  <div class="container" role="main">
    <h2>Customer Service Representative</h2>
    <p class="sub">Search and manage open requests, maintain your shortlist, and review your volunteer service history.</p>

    <div class="alerts" aria-live="polite">
      <?php if ($success === 1): ?>
        <div class="alert alert-success">✅ Action completed successfully.</div>
      <?php endif; ?>
      <?php if ($errorMsg !== ''): ?>
        <div class="alert alert-error">⚠️ <?php echo $errorMsg; ?></div>
      <?php endif; ?>
    </div>

    <div class="grid">
      <!-- Request Board (User Stories #23, #24) -->
      <div class="card">
        <h3>Request Board</h3>
        <p>Search and view open volunteer service requests. Browse opportunities and review detailed information.</p>
        <div class="actions">
          <a class="btn btn-primary" href="csr_request_board.php">View Board</a>
        </div>
      </div>

      <!-- My Shortlist (User Stories #25, #26, #27) -->
      <div class="card">
        <h3>My Shortlist</h3>
        <p>Save and review requests you're considering. Search and manage your saved opportunities.</p>
        <div class="actions">
          <a class="btn btn-secondary" href="csr_shortlist_view.php">View Shortlist</a>
        </div>
      </div>

      <!-- Service History (User Stories #28, #29) -->
      <div class="card card-full">
        <h3>Service History</h3>
        <p>View your history of completed volunteer services and filter by date or service type.</p>
        <div class="actions">
          <a class="btn btn-accent" href="csr_service_history.php">View History</a>
        </div>
      </div>
    </div>

    <!-- Hidden CSRF token -->
    <form style="display:none">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8'); ?>">
    </form>
  </div>
</body>
</html>
