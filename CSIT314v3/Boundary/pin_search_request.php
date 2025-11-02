<?php
declare(strict_types=1);
ini_set('display_errors','1'); 
error_reporting(E_ALL);
session_start();

/* Only PIN can access (kept exactly as requested) */
if (empty($_SESSION['user_id']) || (($_SESSION['profile_type'] ?? '') !== 'pin')) {
  header('Location: login.php'); exit;
}

require_once __DIR__ . '/../Controller/PinViewRequestsController.php';
use App\Controller\PinViewRequestsController;

/* ---------------- FUNCTIONS (No Classes) ---------------- */

function getFilters(): array {
    $status = isset($_GET['status']) ? trim((string)$_GET['status']) : '';
    $q      = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $page   = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
    return [$status, $q, $page];
}

function loadRequests(int $userId, string $status, string $q, int $page): array {
    $ctl = new PinViewRequestsController();
    return $ctl->list($userId, $status ?: null, $q ?: null, $page, 10);
}

/* ---------------- EXECUTION ---------------- */

$userId   = (int)$_SESSION['user_id'];
$userName = htmlspecialchars($_SESSION['name'] ?? 'pin');

/* Read Filters */
[$status, $q, $page] = getFilters();

/* Fetch Data */
$data = loadRequests($userId, $status, $q, $page);
$rows = $data['rows']; 
$pages = $data['pages']; 
$total = $data['total'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>View Requests</title>
<style>
  html, body { min-height: 100vh; }
  html {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
    background-attachment: fixed;
  }
  body {
    margin: 0;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    color: #111827;
    background: transparent;
  }
  .wrap { max-width: 1100px; margin: 28px auto; padding: 0 18px; }
  .topbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border-radius: 20px;
    padding: 18px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 10px 35px rgba(0,0,0,0.15);
  }
  .brand { display: flex; align-items: center; gap: 12px; }
  .title { font-weight: 800; color: #4f46e5; font-size: 22px; }
  .dashboard {
    padding: 10px 16px; border: 0; border-radius: 12px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff; cursor: pointer; font-weight: 800; font-size: 14px;
  }
  .btn {
    padding: 4px 16px; border: 0; border-radius: 12px;
    background: linear-gradient(135deg, #8b5cf6);
    color: #fff; cursor: pointer; font-weight: 500; font-size: 16px;
  }
  .hero {
    margin: 26px 0;
    background: rgba(255, 255, 255, 0.96);
    border-radius: 24px;
    padding: 28px;
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18);
  }
  .hero h1 { margin: 0 0 8px; font-size: 36px; }
  .muted { color: #6b7280; }
  table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(0,0,0,0.18);
  }
  th, td { padding: 14px 16px; border-bottom: 1px solid #e5e7eb; text-align: left; }
  th {
    background: #f8fafc; text-transform: uppercase;
    font-size: 12px; letter-spacing: 0.05em; color: #6b7280;
  }
  tr:last-child td { border-bottom: none; }
  .controls {
    background: #fff;
    border-radius: 14px;
    padding: 12px 14px;
    margin: 16px auto;
    display: flex; gap: 10px; align-items: center;
    justify-content: center;
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    max-width: 700px;
  }
  .controls input, .controls select{
    font-size: 16px; padding: 12px 14px; border-radius: 10px;
    border: 1px solid #e5e7eb; font-family: inherit;
  }
  .status { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
  .status.open { background: #e0e7ff; color: #3730a3; }
  .status.in_progress { background: #fef3c7; color: #92400e; }
  .status.closed { background: #dcfce7; color: #065f46; }
</style>
</head>
<body>
  <div class="wrap">
  <div class="topbar">
    <div class="brand">
      <div class="title">View Requests</div>
    </div>
    <div>
      Welcome, <strong><?= $userName ?></strong>
      &nbsp;&nbsp;
      <a href="pin_dashboard.php"><button class="dashboard">Back to Dashboard</button></a>
    </div>
  </div>
  </div>

  <form class="controls" method="get" action="view_requests.php">
    <input type="text" name="q" placeholder="Search request text…" value="<?= htmlspecialchars($q) ?>">
    <select name="status">
      <option value="">All statuses</option>
      <option value="open"        <?= $status==='open'?'selected':'' ?>>Open</option>
      <option value="in_progress" <?= $status==='in_progress'?'selected':'' ?>>In Progress</option>
      <option value="closed"      <?= $status==='closed'?'selected':'' ?>>Closed</option>
    </select>
    <button class="btn" type="submit">Filter</button>
    <?php if ($q || $status): ?>
      <a class="btn" href="view_requests.php">Reset</a>
    <?php endif; ?>
    <div class="muted" style="margin-left:auto">Total: <?= (int)$total ?></div>
  </form>

  <div class="wrap">
  <div class="hero">
    <h1>My Requests</h1>
    <p class="muted">View all your requests and their current status.</p>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Request</th>
          <th>Location</th>
          <th>Status</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="5">No requests found.</td></tr>
        <?php else: foreach ($rows as $r): $s=strtolower((string)$r['status']); ?>
          <tr>
            <td><?= (int)$r['request_id'] ?></td>
            <td><?= nl2br(htmlspecialchars($r['content'])) ?></td>
            <td><?= htmlspecialchars($r['location']) ?></td>
            <td><span class="status <?= htmlspecialchars($s) ?>"><?= htmlspecialchars($r['status']) ?></span></td>
            <td><?= htmlspecialchars((string)$r['created_at']) ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  </div>

  <?php if ($pages > 1): ?>
    <div class="pager">
      <?php for ($p=1; $p <= $pages; $p++): ?>
        <?php $qs = http_build_query(['q'=>$q,'status'=>$status,'page'=>$p]); ?>
        <a class="btn" href="view_requests.php?<?= $qs ?>"<?= $p===$page?' style="opacity:.8"':'' ?>><?= $p ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
