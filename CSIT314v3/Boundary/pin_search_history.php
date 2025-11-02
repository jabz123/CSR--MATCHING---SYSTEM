<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
ini_set('display_errors','1'); 
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) { session_start(); }

/* ---------------- AUTH FUNCTION ---------------- */
function restrictToPIN(): void {
    if (empty($_SESSION['user_id']) || (($_SESSION['profile_type'] ?? '') !== 'pin')) {
        header('Location: ../login.php');
        exit;
    }
}

/* ---------------- LOAD FUNCTIONS ---------------- */
function loadHistory(int $pinId, ?string $status): array {
    require_once __DIR__ . '/../Controller/PinViewHistoryController.php';
    $ctl = new \App\Controller\PinViewHistoryController();
    return $ctl->listForPin($pinId, $status ?: null);
}

function loadSearchResults(int $pinId, ?string $q, ?string $from, ?string $to): array {
    require_once __DIR__ . '/../Controller/PinSearchHistoryController.php';
    $ctl = new \App\Controller\PinSearchHistoryController();
    return $ctl->search($pinId, $q, $from, $to);
}

/* ---------------- EXECUTION FLOW ---------------- */
restrictToPIN();

$pinId  = (int)$_SESSION['user_id'];
$status = $_GET['status'] ?? '';
$q      = $_GET['q'] ?? null;
$from   = $_GET['from'] ?? null;
$to     = $_GET['to'] ?? null;

$doSearch = (($q ?? '') !== '') || (($from ?? '') !== '') || (($to ?? '') !== '');

$heading = 'My History';

if ($doSearch) {
    $rows = loadSearchResults($pinId, $q, $from, $to);
} else {
    $rows = loadHistory($pinId, $status);
}

/* ✅ Table Renderer */
require_once __DIR__ . '/pin_history_table.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= $heading ?></title>
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
  table{width:100%;border-collapse:collapse}
  th,td{padding:12px 10px;border-bottom:1px solid #eee;text-align:left;vertical-align:top}
  .muted{color:#666}
  .empty{padding:18px;color:#666}
  .topbar{display:flex;justify-content:space-between;align-items:center;margin:0 0 14px}
  .btn.ghost{background:#fff;color:#4f46e5;border:1px solid #4f46e5}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <div class="topbar">
      <h1><?= htmlspecialchars($heading) ?></h1>
      <a class="btn ghost" href="pin_dashboard.php">Back to Dashboard</a>
    </div>

    <div class="row">
      <form class="search" method="get" action="" title="Search completed by service/date">
        <input type="text" name="q" placeholder="Search service (title/description)…"
               value="<?= htmlspecialchars((string)($q ?? '')) ?>">
        <input type="date" name="from" value="<?= htmlspecialchars((string)($from ?? '')) ?>">
        <input type="date" name="to"   value="<?= htmlspecialchars((string)($to   ?? '')) ?>">
        <button type="submit">Search</button>
        <a class="btn" href="?">Clear</a>
      </form>
    </div>

    <?php \render_history_table($rows); ?>
  </div>
</div>
</body>
</html>
