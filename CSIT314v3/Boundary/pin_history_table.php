<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

session_start();

use App\Controller\PinViewHistoryController;
require_once __DIR__ . '/../Controller/PinViewHistoryController.php';
use App\Controller\PinSearchHistoryController;
require_once __DIR__ . '/../Controller/PinSearchHistoryController.php';


/* ---------------- ENTRY POINT ---------------- */
function handleRequest(): void
{
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['profile_type'] ?? '') !== 'pin') {
        header('Location: ../login.php');
        exit;
    }

    $pinId = (int)$_SESSION['user_id'];
    $data  = loadHistory($pinId);

    renderPage($data);
}

/* ---------------- CONTROLLER ACCESS ---------------- */
function loadHistory(int $pinId): array
{
    $q      = isset($_GET['q'])     ? trim((string)$_GET['q'])     : null;
    $date   = isset($_GET['date'])  ? trim((string)$_GET['date'])  : null;
    $status = isset($_GET['status'])? trim((string)$_GET['status']): '';

    $q    = ($q === '')    ? null : $q;
    $date = ($date === '') ? null : $date;

    if ($date !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $date = null;
    }

    $from = $date;
    $to   = $date;

    $controller = new PinViewHistoryController();
    $searchCtl  = new PinSearchHistoryController();

    $doSearch = ($q !== null) || ($date !== null);

    if ($doSearch) {
        $rows = $searchCtl->search($pinId, $q, $date, 200, 0);
    } else {
        $rows = $controller->listForPin($pinId, $status ?: null, 200, 0);
    }

    return [
        'rows'     => $rows,
        'q'        => $q,
        'date'     => $date,      
        'status'   => $status,
        'doSearch' => $doSearch
    ];
}

/* ---------------- RENDER PAGE ---------------- */
function renderPage(array $data): void
{
    $rows     = $data['rows'];
    $q        = (string)($data['q'] ?? '');
    $date     = (string)($data['date'] ?? '');
    $heading  = ($data['doSearch']) ? 'Search Results' : 'My History';
    ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  html, body { height: 100%; }
  body{
    margin: 0;
    min-height: 100%;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
    background-repeat: no-repeat;
    background-attachment: fixed;
    font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;
  }
  .wrap{ max-width:1100px; margin:28px auto; padding:0 18px; }
  .card{
    background: rgba(255,255,255,.96);
    backdrop-filter: blur(6px);
    border-radius: 18px;
    box-shadow: 0 18px 45px rgba(0,0,0,.18);
    padding: 22px;
  }
  h1{margin:0 0 16px}
  .row{display:flex;gap:16px;flex-wrap:wrap;margin:6px 0 16px}
  form.search{display:grid;grid-template-columns:1.6fr 1fr 1fr auto auto;gap:10px;}
  input[type="text"],input[type="date"],select{padding:10px;border:1px solid #ddd;border-radius:10px;background:#fff}
  button,.btn{padding:10px 16px;border:0;border-radius:10px;background:#4f46e5;color:#fff;cursor:pointer;text-decoration:none;font-size:16px}
  table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden}
  th,td{padding:12px 10px;border-bottom:1px solid #eee;text-align:left;vertical-align:top}
  .muted{color:#666}
  .empty{padding:18px;color:#666;background:#fff;border-radius:12px}
  .topbar{display:flex;justify-content:space-between;align-items:center;margin:0 0 14px}
  .btn.ghost{background:#fff;color:#4f46e5;border:1px solid #4f46e5}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <div class="topbar">
      <h1><?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></h1>
      <a class="btn ghost" href="pin_dashboard.php">Back to Dashboard</a>
    </div>

    <div class="row">
      <form class="search" method="get" action="">
        <input type="text" name="q" placeholder="Search service (title/description)…"
               value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>">
        <input type="date" name="date" value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit">Search</button>
        <a class="btn" href="?">Clear</a>
      </form>
    </div>

    <?php if (empty($rows)): ?>
      <div class="empty">No records found.</div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Request</th>
            <th>Volunteer</th>
            <th>Status</th>
            <th>Completed At</th>
            <th>Title</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)($r['history_id'] ?? 0) ?></td>
            <td><?= (int)($r['request_id'] ?? 0) ?></td>
            <td><?= htmlspecialchars((string)($r['volunteer_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)($r['status'] ?? ''),        ENT_QUOTES, 'UTF-8') ?></td>
            <td class="muted">
              <?php
                $completedAt = $r['completed_at'] ?? null;
                echo $completedAt
                  ? htmlspecialchars(date('M d, Y H:i', strtotime((string)$completedAt)), ENT_QUOTES, 'UTF-8')
                  : '-';
              ?>
            </td>
            <td><?= htmlspecialchars((string)($r['title'] ?? ''),         ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)($r['description'] ?? ''),   ENT_QUOTES, 'UTF-8') ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
<?php
}
/* ---------------- RUN PAGE ---------------- */
handleRequest();
