<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
session_start();

// ✅ LOAD CONTROLLERS
require_once $_SERVER['DOCUMENT_ROOT'] . '/CSIT314v3/Controller/CSRViewRequestDetailsController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/CSIT314v3/Controller/PinNumOfViewController.php';

use App\Controller\CSRViewRequestDetailsController;
use App\Controller\PinNumOfViewController;

/** ✅ Restrict access to CSR only */
function restrictAccess(): void {
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['profile_type'] ?? '') !== 'csr') {
        header('Location: ../login.php');
        exit;
    }
}

/** ✅ Validate & return request ID */
function getRequestId(): int {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        exit('❌ Invalid request ID.');
    }

    $requestId = (int)$_GET['id'];
    if ($requestId <= 0) {
        header('Location: csr_request_board.php');
        exit;
    }
    return $requestId;
}

/** ✅ Only increment view count once every 30 mins per CSR */
function incrementViewCount(int $requestId): void {
    $viewerId = (int)$_SESSION['user_id'];
    $key = "req_viewed_{$requestId}_by_{$viewerId}";
    $now = time();

    if (empty($_SESSION[$key]) || ($now - (int)$_SESSION[$key]) >= 1800) {
        $ctl = new PinNumOfViewController();
        $ctl->increment($requestId);
        $_SESSION[$key] = $now;
    }
}

/** ✅ Load request details from controller */
function loadRequestDetails(int $requestId): array {
    $controller = new CSRViewRequestDetailsController();
    $request = $controller->viewDetails($requestId);

    if (!$request) {
        exit('❌ Request not found.');
    }
    return $request;
}

/** ✅ Render page (HTML unchanged) */
function renderPage(array $request): void {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CSR - Request Details</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
/* 🔥 Your entire CSS kept untouched */
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: linear-gradient(135deg, #6366f1, #8b5cf6, #d946ef);
  min-height: 100vh;
  margin: 0;
  padding: 40px;
  color: #111827;
}
.container {
  max-width: 700px;
  margin: 0 auto;
  background: #fff;
  border-radius: 16px;
  padding: 30px 40px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
h1 { color: #4f46e5; margin-bottom: 25px; text-align: center; }
.section { margin-bottom: 18px; }
.section label {
  display: block;
  font-weight: 600;
  color: #374151;
  margin-bottom: 6px;
}
.section p {
  background: #f9fafb;
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}
.btn {
  display: inline-block;
  margin-top: 25px;
  padding: 10px 18px;
  border-radius: 8px;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: white;
  text-decoration: none;
  font-weight: 600;
  transition: background 0.2s ease;
}
.btn:hover { background: #4f46e5; }
.header-bar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background: #4f46e5;
  color: white;
  padding: 12px 20px;
  font-weight: 600;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.header-bar a {
  color: white;
  text-decoration: none;
  float: right;
  background: rgba(255,255,255,0.15);
  padding: 6px 12px;
  border-radius: 6px;
}
.header-bar a:hover { background: rgba(255,255,255,0.3); }
</style>
</head>
<body>

<div class="header-bar">
  CSR Request Details
  <a href="csr_request_board.php">← Back to Board</a>
</div>

<div class="container" style="margin-top: 60px;">
  <h1>Request Details</h1>

  <div class="section"><label>Title</label>
    <p><?= htmlspecialchars($request['title'] ?? 'N/A') ?></p>
  </div>

  <div class="section"><label>Description</label>
    <p><?= nl2br(htmlspecialchars($request['content'] ?? 'N/A')) ?></p>
  </div>

  <div class="section"><label>Category</label>
    <p><?= htmlspecialchars($request['category_name'] ?? 'N/A') ?></p>
  </div>

  <div class="section"><label>Location</label>
    <p><?= htmlspecialchars($request['location'] ?? 'N/A') ?></p>
  </div>

  <div class="section"><label>Status</label>
    <p><?= htmlspecialchars($request['status'] ?? 'N/A') ?></p>
  </div>

  <div class="section"><label>Created At</label>
    <p><?= htmlspecialchars($request['created_at'] ?? 'N/A') ?></p>
  </div>

  <div class="section"><label>Requested By</label>
    <p><?= htmlspecialchars($request['homeowner_name'] ?? 'Unknown') ?></p>
  </div>

  <a href="csr_request_board.php" class="btn">← Back to Request Board</a>
</div>

</body>
</html>
<?php
}

/* ---------- PAGE EXECUTION FLOW ---------- */
restrictAccess();
$requestId = getRequestId();
incrementViewCount($requestId);
$request = loadRequestDetails($requestId);
renderPage($request);
