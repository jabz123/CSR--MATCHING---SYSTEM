<?php
declare(strict_types=1);
session_start();


use App\Controller\CSRSearchRequestsController;
require_once $_SERVER['DOCUMENT_ROOT'] . '/CSIT314v3/Controller/CSRSearchRequestsController.php';

// ✅ Restrict access: CSR only
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['profile_type'] ?? '') !== 'csr') {
    header('Location: login.php');
    exit;
}

// =========================
// FUNCTIONS (Boundary Layer)
// =========================

/** ✅ Get and sanitize search term */
function getSearchTerm(): string {
    return isset($_GET['q']) ? trim($_GET['q']) : '';
}

/** ✅ Load requests from controller */
function loadRequests(string $searchTerm): array {
    $controller = new CSRSearchRequestsController();
    return $controller->searchRequests($searchTerm);
}

/** ✅ Render the page */
function renderPage(array $requests, string $searchTerm, string $userName): void {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CSR Request Board</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
/* ✅ Your CSS unchanged */
  body { font-family: Arial, sans-serif; background: #f4f4fc; margin: 0; padding: 0; }
  header { background: #4f46e5; color: white; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; }
  header h2 { margin: 0; }
  header a { color: white; text-decoration: none; font-weight: 600; background: rgba(255,255,255,0.15); padding: 6px 12px; border-radius: 8px; }
  header a:hover { background: rgba(255,255,255,0.25); }
  .container { padding: 20px; max-width: 1100px; margin: auto; }
  h1 { color: #4f46e5; }
  form { margin-bottom: 15px; }
  input[type="text"] { padding: 8px; width: 280px; border-radius: 6px; border: 1px solid #ccc; }
  button { padding: 8px 14px; background: #4f46e5; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
  button:hover { background: #4338ca; }
  table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; margin-top: 15px; }
  th, td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; text-align: left; }
  th { background: #eef2ff; color: #4f46e5; }
  tr:hover { background: #f9fafb; }
  .badge { padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; color: white; }
  .badge-open { background: #2563eb; }
  .badge-in_progress { background: #f59e0b; }
  .badge-closed { background: #10b981; }
  .btn-view { background: #4f46e5; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s ease; }
  .btn-view:hover { background: #4338ca; }
  .btn-shortlist { background: #f59e0b; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; }
  .btn-shortlist:hover { background: #d97706; }
  .btn-shortlist:disabled { background: #9ca3af; cursor: not-allowed; }
</style>

<script>
function addToShortlist(requestId, button) {
  fetch('/CSIT314v3/Boundary/csr_shortlist_add.php?id=' + encodeURIComponent(requestId))
    .then(res => res.text())
    .then(response => {
      if (response.includes('success=1')) {
        button.textContent = '✅ Shortlisted';
        button.disabled = true;
        button.style.background = '#10b981';
        return;
      }
      if (response.includes('error=duplicate')) {
        alert('⚠️ Already in shortlist.');
        button.textContent = '✅ Shortlisted';
        button.disabled = true;
        button.style.background = '#10b981';
        return;
      }
      alert('Server said: ' + (response || '(empty)'));
    })
    .catch(() => alert('⚠️ Network error.'));
}
</script>
</head>
<body>

<header>
  <h2>CSR Request Board</h2>
  <div>
    <a href="csr_shortlist_view.php">⭐ My Shortlist</a>
    <a href="csr_dashboard.php">← Back to Dashboard</a>
  </div>
</header>

<div class="container">
  <h1>Open Requests</h1>

  <form method="get">
    <input type="text" name="q" placeholder="Search by title, content, or location"
           value="<?= htmlspecialchars($searchTerm) ?>">
    <button type="submit">Search</button>
  </form>

  <?php if (empty($requests)): ?>
    <p>No open requests found.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Location</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($requests as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['title'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['location'] ?? '') ?></td>
            <td><span class="badge badge-<?= strtolower($r['status'] ?? 'open') ?>">
              <?= htmlspecialchars(ucfirst($r['status'] ?? 'Open')) ?></span></td>
            <td><?= htmlspecialchars(date('M d, Y', strtotime($r['created_at'] ?? 'now'))) ?></td>
            <td>
              <a class="btn-view" href="csr_request_details.php?id=<?= urlencode((string)$r['request_id']) ?>">View</a>
              <button class="btn-shortlist" onclick="addToShortlist(<?= (int)$r['request_id'] ?>, this)">
                ⭐ Add to Shortlist
              </button>
            </td>
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

// =========================
// RUN PAGE
// =========================

$searchTerm = getSearchTerm();
$requests = loadRequests($searchTerm);
$userName = htmlspecialchars($_SESSION['username'] ?? 'CSR Rep', ENT_QUOTES, 'UTF-8');

renderPage($requests, $searchTerm, $userName);
