<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
session_start();

// 0) Checkpoint
echo "<!-- checkpoint: start -->\n";

// 1) Role guard
if (empty($_SESSION['user_id'])) {
    echo "<pre style='color:#b00;background:#fee;padding:10px;border:1px solid #fbb'>SESSION missing user_id. Redirecting to login…</pre>";
    header('Location: login.php'); exit;
}
if (($_SESSION['profile_type'] ?? '') !== 'pin') {
    echo "<pre style='color:#b00;background:#fee;padding:10px;border:1px solid #fbb'>SESSION profile_type is not 'PIN'. Got: "
         . htmlspecialchars((string)($_SESSION['profile_type'] ?? 'NULL'))
         . " → redirecting to login…</pre>";
    header('Location: login.php'); exit;
}

echo "<!-- checkpoint: role ok -->\n";

// 2) CSRF token
if (empty($_SESSION['_csrf'])) {
    $_SESSION['_csrf'] = bin2hex(random_bytes(32));
}

// 3) Handle POST (instrumented)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['action'] ?? '') === 'create_request') {
    echo "<!-- checkpoint: entered POST handler -->\n";

    $content  = trim((string)($_POST['content'] ?? ''));
    $location = trim((string)($_POST['location'] ?? ''));
    $title = trim((string)($_POST['title'] ?? ''));
    $csrfOk   = !empty($_POST['_csrf']) && hash_equals($_SESSION['_csrf'] ?? '', (string)$_POST['_csrf']);

    if (!$csrfOk) {
        $_SESSION['flash'] = '❌ Invalid submission. Please try again.';
    } elseif ($location === '' || mb_strlen($location) > 255 || $content === '' || mb_strlen($content) > 4000 || $title === '') {
        $_SESSION['flash'] = '⚠️ Please enter a valid location and request.';
    } else {
        $ctlPath = __DIR__ . '/../Controller/PinCreateRequestController.php';
        if (!file_exists($ctlPath)) {
            echo "<pre style='color:#b00;background:#fee;padding:10px;border:1px solid #fbb'>Controller file missing: "
                 . htmlspecialchars($ctlPath) . "</pre>";
            exit;
        }
        require_once $ctlPath;

        if (!class_exists('\\App\\Controller\\PinCreateRequestController')) {
            echo "<pre style='color:#b00;background:#fee;padding:10px;border:1px solid #fbb'>Controller class not found: \\App\\Controller\\PinCreateRequestController</pre>";
            exit;
        }

        try {
            $controller = new \App\Controller\PinCreateRequestController();
            $ok = $controller->create((int)$_SESSION['user_id'], $content, $location, $title);
            $_SESSION['flash'] = $ok ? '✅ Request created successfully.' : '❌ Could not create request.';
        } catch (\Throwable $e) {
            echo "<pre style='color:#b00;background:#fee;padding:10px;border:1px solid #fbb'>Controller EXCEPTION: "
                 . htmlspecialchars($e->getMessage()) . "\nFile: " . $e->getFile() . ':' . $e->getLine() . "</pre>";
            exit;
        }
    }

    header('Location: pin_dashboard.php'); exit; // PRG
}
$userName = htmlspecialchars($_SESSION['name'] ?? 'pin');
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PIN Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  html, body { height: 100%; }
  html { background: none; }
  body {
    margin: 0;
    min-height: 100%;
    background: linear-gradient(135deg,#6366f1 0%,#8b5cf6 50%,#d946ef 100%);
    background-repeat: no-repeat;
    background-attachment: scroll; /* default */
  }
  .wrap{max-width:1100px;margin:28px auto;padding:0 18px}
  .topbar{background:rgba(255,255,255,.95);backdrop-filter:blur(8px);border-radius:20px;padding:18px 22px;
          display:flex;justify-content:space-between;align-items:center;box-shadow:0 10px 35px rgba(0,0,0,.15)}
  .brand{display:flex;align-items:center;gap:12px}
  .logo{width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);
        display:grid;place-items:center;color:#fff;font-size:22px}
  .title{font-weight:800;color:#4f46e5;font-size:22px}
  .logout{padding:10px 16px;border:0;border-radius:12px;background:linear-gradient(135deg,#ef4444,#dc2626);
          color:#fff;cursor:pointer;font-weight:800;font-size: 16px}
  .hero{margin:26px 0;background:rgba(255,255,255,.96);border-radius:24px;padding:28px;box-shadow:0 12px 40px rgba(0,0,0,.18)}
  .hero h1{margin:0 0 8px;font-size:36px}
  .muted{color:#6b7280}
  .cards{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-top:22px}
  .card{background:#fff;border-radius:20px;padding:20px;box-shadow:0 10px 30px rgba(0,0,0,.15)}
  .card h3{margin:0 0 6px;font-size:20px;font-weight:800}
  .btn{display:inline-block;padding:12px 18px;border-radius:12px;border:0;cursor:pointer;font-weight:800;color:#fff;
       background:linear-gradient(135deg,#6366f1,#8b5cf6);text-decoration:none;box-shadow:0 8px 20px rgba(99,102,241,.35);font-size: 16px}
  .btn:focus,.btn:hover{transform:translateY(-1px)}
  .btn-secondary{background:linear-gradient(135deg,#14b8a6,#0ea5e9)}
  .btn-gray{background:linear-gradient(135deg,#9ca3af,#6b7280)}
  table{width:100%;border-collapse:collapse}
  th,td{padding:12px;border-bottom:1px solid #e5e7eb;text-align:left;vertical-align:top}
  th{background:#f8fafc;font-size:12px;text-transform:uppercase;letter-spacing:.03em;color:#6b7280}
  .status{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700}
  .status.open{background:#e0e7ff;color:#3730a3}
  .status.in_progress{background:#fef3c7;color:#92400e}
  .status.closed{background:#dcfce7;color:#065f46}
  .stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-top:8px}
  .stat{background:#fff;border-radius:16px;padding:14px;text-align:center;box-shadow:0 8px 24px rgba(0,0,0,.12)}
  .big{font-size:28px;font-weight:900;color:#4f46e5}
  .flash{margin:16px 0;background:#ecfdf5;border:1px solid #10b981;color:#065f46;padding:12px 16px;border-radius:12px;font-weight:700}
</style>
</head>
<body>
<div class="wrap">

  <!-- Topbar -->
  <div class="topbar">
    <div class="brand"><div class="logo">🤝</div><div class="title">PIN Dashboard</div></div>
    <div>Welcome, <strong><?= $userName ?></strong>
      &nbsp;&nbsp;<a href="logout.php"><button class="logout">Logout</button></a></div>
  </div>

  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash"><?= htmlspecialchars($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <!-- Hero -->
  <div class="hero">
    <h1>Manage Your Requests</h1>
    <p class="muted">Create a new help request, and track the progress of your existing ones.</p>

    <!-- Action Cards (like your admin screenshot) -->
    <div class="cards">
      <div class="card">
        <h3>Create Request</h3>
        <p class="muted">Describe the help you need and where you are.</p>
        <a class="btn" href="pin_create_request.php">Create Request</a>
      </div>

      <div class="card">
        <h3>My Requests</h3>
        <p class="muted">View your recent requests and their status.</p>
        <br>
        <a class="btn btn-secondary" href="pin_view_requests.php">Manage Requests</a>
      </div>

      <div class="card">
        <h3>History</h3>
        <p class="muted">View all your completed history.</p>
        <br>
        <a class="btn btn-gray" href="pin_history_table.php">Completed Request</a>
      </div>
    </div>
  </div>

</div>
</body>
</html>
