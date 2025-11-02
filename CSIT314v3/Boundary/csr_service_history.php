<?php
declare(strict_types=1);
ini_set('display_errors','1');
ini_set('log_errors','1');
error_reporting(E_ALL);

session_start();

use App\Controller\CSRSearchHistoryController;
require_once $_SERVER['DOCUMENT_ROOT'] . '/CSIT314v3/Controller/CSRSearchHistoryController.php';

function handleRequest(): void
{
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['profile_type'] ?? '') !== 'csr') {
        header('Location: login.php');
        exit;
    }

    $csrId = (int)$_SESSION['user_id'];
    [$history, $keyword, $startDate] = loadHistory($csrId);

    $userName = htmlspecialchars($_SESSION['username'] ?? 'CSR Representative', ENT_QUOTES, 'UTF-8');

    renderPage($history, $keyword, $startDate, $userName);
}

function loadHistory(int $csrId): array
{
    $keyword   = trim($_GET['q'] ?? '');
    $startDate = trim($_GET['start'] ?? '');
    $endDate   = trim($_GET['end'] ?? ''); // NEW

    $controller = new CSRSearchHistoryController();
    $history = $controller->searchHistory($csrId, $keyword, $startDate, $endDate);

    return [$history, $keyword, $startDate];
}

function renderPage(array $history, string $keyword, string $startDate, string $userName): void
{
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CSR Service History</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4fc; margin: 0; padding: 0; }
  header { background: #4f46e5; color: white; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
  header a { color: white; text-decoration: none; font-weight: 600; background: rgba(255,255,255,0.15); padding: 6px 12px; border-radius: 8px; }
  header a:hover { background: rgba(255,255,255,0.25); }
  .container { padding: 20px; max-width: 1000px; margin: auto; }
  h1 { color: #4f46e5; }
  form { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
  input, button { padding: 8px; border-radius: 6px; border: 1px solid #ccc; }
  button { background: #4f46e5; color: white; border: none; font-weight: 600; cursor: pointer; }
  button:hover { background: #4338ca; }
  table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
  th, td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
  th { background: #eef2ff; color: #4f46e5; text-align: left; }
  tr:hover { background: #f9fafb; }
  .badge { padding: 4px 10px; border-radius: 12px; color: white; font-size: 0.8rem; }
  .badge-completed { background: #10b981; }
  .badge-cancelled { background: #ef4444; }
  .btn{padding:10px 16px;border:0;border-radius:10px;background:#4f46e5;color:#fff;cursor:pointer;text-decoration:none;font-size:14px;font-weight: 500}
  .btn1{padding:10px 16px;border:0;border-radius:10px;background:#4f46e5;color:#fff;cursor:pointer;text-decoration:none;font-size:14px; font-weight: 500}

</style>
</head>
<body>

<header>
  <h2>🕓 Service History</h2>
  <a href="csr_dashboard.php">← Back to Dashboard</a>
</header>

<div class="container">
  <h1>My Completed Volunteer Services</h1>

  <form method="get">
    <input type="text" name="q" placeholder="Search by remarks" value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>">
    <input type="date" name="start" value="<?= htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8') ?>">
    <button class="btn1" type="submit">🔍 Filter</button>
    <a class="btn" href="?">Clear</a>
  </form>

  <?php if (empty($history)): ?>
    <p>No completed services<?= $keyword ? ' matching "' . htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') . '"' : '' ?>.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Remarks</th>
          <th>Hours Served</th>
          <th>Status</th>
          <th>Completed At</th>
          <th>Volunteer ID</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($history as $h): ?>
          <tr>
            <td><?= htmlspecialchars((string)($h['remarks'] ?? 'N/A'), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars((string)($h['hours_served'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
            <td>
              <span class="badge badge-<?= strtolower((string)($h['status'] ?? '')) ?>">
                <?= htmlspecialchars(ucfirst((string)($h['status'] ?? '')), ENT_QUOTES, 'UTF-8') ?>
              </span>
            </td>
            <td>
              <?php
                $completedAt = $h['completed_at'] ?? null;
                echo $completedAt
                  ? htmlspecialchars(date('M d, Y H:i', strtotime((string)$completedAt)), ENT_QUOTES, 'UTF-8')
                  : '-';
              ?>
            </td>
            <td><?= htmlspecialchars((string)($h['volunteer_id'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

</body>
</html>
<?php
}
handleRequest();
