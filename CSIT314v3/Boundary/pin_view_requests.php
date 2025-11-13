<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

session_start();

require_once __DIR__ . '/../Controller/PinViewRequestsController.php';
use App\Controller\PinViewRequestsController;

require_once __DIR__ . '/../Controller/PinNumOfViewController.php';
use App\Controller\PinNumOfViewController;

require_once __DIR__ . '/../Controller/PinViewShortlistController.php';
use App\Controller\PinViewShortlistController;

require_once __DIR__ . '/../Controller/PinSearchRequestsController.php';
use App\Controller\PinSearchRequestsController;

if (empty($_SESSION['user_id']) || (($_SESSION['profile_type'] ?? '') !== 'pin')) {
  header('Location: login.php'); exit;
}

if (empty($_SESSION['_csrf'])) {
  $_SESSION['_csrf'] = bin2hex(random_bytes(32));
}

function flash_message(): array {
    $msg = '';
    $type = 'success';

    foreach (['flash_edit_request', 'flash'] as $k) {
        if (!empty($_SESSION[$k])) { $msg = (string)$_SESSION[$k]; break; }
    }

    if (!empty($_SESSION['flash_type']) && in_array($_SESSION['flash_type'], ['success','error'], true)) {
        $type = $_SESSION['flash_type'];
    } elseif (strpos($msg, '❌') !== false) {
        $type = 'error';
    }

    unset($_SESSION['flash_edit_request'], $_SESSION['flash'], $_SESSION['flash_type']);
    return [$msg, $type];
}

function csrf_valid(): bool {
    return !empty($_POST['_csrf']) && hash_equals($_SESSION['_csrf'], (string)$_POST['_csrf']);
}

function get_filters(): array {
    $status = $_GET['status'] ?? 'all';
    $q = trim((string)($_GET['q'] ?? ''));
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = max(1, min(50, (int)($_GET['perPage'] ?? 10)));
    return [$status, $q, $page, $perPage];
}

function load_requests(int $userId, ?string $status, ?string $q, int $page, int $perPage): array {
    if ($q !== null && $q !== '') {
        // Use the SEARCH controller when keyword exists
        $searchCtl = new PinSearchRequestsController();
        return $searchCtl->search($userId, $q, $status, $page, $perPage);
    }

    // Otherwise, use the LIST controller
    $listCtl = new PinViewRequestsController();
    return $listCtl->list($userId, $status, $page, $perPage);
}

function view_count(int $id): int {
    $ctl = new PinNumOfViewController();
    return (int)$ctl->getCountForView($id);
}

function shortlist_count(int $id): int {
    $ctl = new PinViewShortlistController();
    return (int)$ctl->getCountForShortlist($id);
}


list($flash, $type) = flash_message();
[$status, $q, $page, $perPage] = get_filters();

$userId = (int)$_SESSION['user_id'];
$userName = htmlspecialchars($_SESSION['name'] ?? 'pin');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!csrf_valid()) {
        $_SESSION['flash'] = '❌ Invalid request (csrf).';
        header('Location: pin_view_requests.php'); exit;
    }
}

$normStatus = ($status === 'all' ? null : $status);
$data = load_requests($userId, $normStatus, $q, $page, $perPage);

$rows  = $data['rows'] ?? [];
$pages = (int)($data['pages'] ?? 1);
$total = (int)($data['total'] ?? 0);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>View Requests</title>

<style>
  html, body { min-height: 100vh; }
  html { background: linear-gradient(135deg,#6366f1 0%,#8b5cf6 50%,#d946ef 100%); background-attachment: fixed; }
  body { margin:0; font-family: system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; color:#111827; background:transparent; }
  .wrap { max-width: 1100px; margin: 28px auto; padding: 0 18px; }
  .topbar { background: rgba(255,255,255,.95); backdrop-filter: blur(8px); border-radius: 20px; padding:18px 22px;
            display:flex; justify-content:space-between; align-items:center; box-shadow:0 10px 35px rgba(0,0,0,.15); }
  .title { font-weight:800; color:#4f46e5; font-size:22px; }
  .dashboard { padding:10px 16px; border:0; border-radius:12px; background:linear-gradient(135deg,#ef4444,#dc2626);
               color:#fff; cursor:pointer; font-weight:800; font-size:14px; }
  .btn { padding:8px 16px; border:0; border-radius:12px; background:linear-gradient(135deg,#8b5cf6,#6366f1);
         color:#fff; cursor:pointer; font-weight:700; font-size:14px; text-decoration:none; display:inline-block; }
  .controls { background:#fff; border-radius:14px; padding:12px 14px; margin:16px auto; display:flex; gap:10px; align-items:center;
              justify-content:center; box-shadow:0 4px 16px rgba(0,0,0,.06); max-width:700px; }
  .controls input, .controls select { font-size:16px; padding:12px 14px; border-radius:10px; border:1px solid #e5e7eb; font:inherit; }
  .hero { margin:26px 0; background:rgba(255,255,255,.96); border-radius:24px; padding:28px; box-shadow:0 12px 40px rgba(0,0,0,.18); }
  .hero h1 { margin:0 0 8px; font-size:36px; }
  .muted { color:#6b7280; }
  table { width:100%; border-collapse:collapse; background:#fff; border-radius:18px; overflow:hidden; box-shadow:0 12px 40px rgba(0,0,0,.18); }
  th,td { padding:14px 16px; border-bottom:1px solid #e5e7eb; text-align:left; vertical-align:top; }
  th { background:#f8fafc; text-transform:uppercase; font-size:12px; letter-spacing:.05em; color:#6b7280; }
  tr:last-child td { border-bottom:none; }
  .status { display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; text-transform:capitalize; }
  .status.open { background:#e0e7ff; color:#3730a3; }
  .status.in_progress { background:#fef3c7; color:#92400e; }
  .status.closed { background:#dcfce7; color:#065f46; }
  .toggle-btn { padding:6px 12px; border:0; border-radius:8px; background:linear-gradient(135deg,#3b82f6,#2563eb);
                color:#fff; cursor:pointer; font-weight:600; font-size:13px; margin-right:10px; }
  .toggle-btn:hover { opacity:0.9; }
  .desc-wrap { padding:10px 4px; }
  .desc-label { font-size:.85rem; color:#6b7280; margin-bottom:4px; text-transform:uppercase; }
  .desc-text { white-space:pre-wrap; line-height:1.45; }
</style>
</head>
<body>

<div class="wrap">
  <div class="topbar">
    <div class="title">View Requests</div>
    <div>
      Welcome, <strong><?= $userName ?></strong>
      &nbsp;&nbsp;<a href="pin_dashboard.php"><button class="dashboard">Back to Dashboard</button></a>
    </div>
  </div>
</div>

<?php if ($flash): ?>
  <div style="background:#ecfdf5;border:1px solid #10b981;color:#065f46;
              padding:12px 16px;margin:16px auto;border-radius:12px;
              font-weight:700;max-width:1100px;">
    <?= htmlspecialchars($flash) ?>
  </div>
<?php endif; ?>

<form class="controls" method="get" action="pin_view_requests.php">
  <input type="text" name="q" placeholder="Search request text…" value="<?= htmlspecialchars($q) ?>">
  <select name="status">
    <option value="">All statuses</option>
    <option value="open"        <?= $status==='open'?'selected':'' ?>>Open</option>
    <option value="in_progress" <?= $status==='in_progress'?'selected':'' ?>>In Progress</option>
    <option value="closed"      <?= $status==='closed'?'selected':'' ?>>Closed</option>
  </select>
  <button class="btn" type="submit">Filter</button>
  <?php if ($q || $status): ?>
    <a class="btn" href="pin_view_requests.php">Reset</a>
  <?php endif; ?>
  <div class="muted" style="margin-left:auto">Total: <?= $total ?></div>
</form>

<div class="wrap">
  <div class="hero">
    <h1>My Requests</h1>
    <p class="muted">View all your requests and their current status.</p>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$rows): ?>
        <tr><td colspan="3">No requests yet.</td></tr>
      <?php else: foreach ($rows as $r):
        $rid       = (int)($r['request_id'] ?? $r['id'] ?? 0);
        $title     = htmlspecialchars($r['title'] ?? 'Untitled');
        $category  = htmlspecialchars($r['category_name'] ?? 'N/A');
        $content   = nl2br(htmlspecialchars($r['content'] ?? ''));
        $loc       = htmlspecialchars($r['location'] ?? '');
        $rowStatus = htmlspecialchars($r['status'] ?? 'open');
        $sClass    = strtolower(str_replace('-', '_', $rowStatus));
        $created   = htmlspecialchars((string)($r['created_at'] ?? ''));
      ?>
        <tr class="req-row" data-id="<?= $rid ?>">
          <td><?= $rid ?></td>
          <td><?= $title ?></td>
          <td>
            <button class="toggle-btn" data-target="desc-<?= $rid ?>" aria-expanded="false">View Details</button>
            <a class="btn" href="pin_edit_request.php?id=<?= $rid ?>">Edit</a>
            <form method="post" action="pin_delete_request.php" style="display:inline"
                onsubmit="return confirm('Delete this request? This cannot be undone.');">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['_csrf']) ?>">
            <input type="hidden" name="id" value="<?= (int)$r['request_id'] ?>">
            <button type="submit" class="btn btn-gray">Delete</button>
            </form>
          </td>
        </tr>
        <tr id="desc-<?= $rid ?>" class="req-desc" hidden>
          <td colspan="3">
            <div class="desc-wrap">
              <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:16px">
                <div>
                  <div class="desc-label">Category</div>
                  <div><?= $category ?></div>
                </div>
                <div>
                  <div class="desc-label">Location</div>
                  <div><?= $loc ?></div>
                </div>
                <div>
                  <div class="desc-label">Status</div>
                  <div><span class="status <?= $sClass ?>"><?= $rowStatus ?></span></div>
                </div>
                <div>
                  <div class="desc-label">Created</div>
                  <div><?= $created ?></div>
                </div>
                <div>
                  <div class="desc-label">Description</div>
                  <div class="desc-text"><?= $content ?></div>
                </div>
                <div>
                    <div class="desc-label">View Count</div>
                    <div><?= view_count($rid) ?></div>
                    <br>
                    <div class="desc-label">Shortlist Count</div>
                    <div><?= shortlist_count($rid) ?></div>
                </div>
            </div>
          </td>
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
      <a class="btn" href="pin_view_requests.php?<?= $qs ?>"<?= $p===$page?' style="opacity:.8"':'' ?>><?= $p ?></a>
    <?php endfor; ?>
  </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();

      const id = btn.getAttribute('data-target');
      const row = document.getElementById(id);

      const nextExpanded = btn.getAttribute('aria-expanded') !== 'true';
      btn.setAttribute('aria-expanded', String(nextExpanded));
      btn.textContent = nextExpanded ? 'Hide Details' : 'View Details';

      if (row) row.hidden = !nextExpanded;
    });
  });
});
</script>
</body>
</html>
