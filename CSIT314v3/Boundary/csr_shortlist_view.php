<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
session_start();

// ✅ Include both controllers
require_once $_SERVER['DOCUMENT_ROOT'] . '/CSIT314v3/Controller/CSRSearchShortlistController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/CSIT314v3/Controller/CSRViewShortlistController.php';

use App\Controller\CSRSearchShortlistController;
use App\Controller\CSRViewShortlistController;

/**
 * ✅ Ensure only CSR users can access
 */
function authorizeCSR(): void {
    if (!isset($_SESSION['user_id']) || strtolower($_SESSION['profile_type'] ?? '') !== 'csr') {
        header('Location: login.php');
        exit;
    }
}

/**
 * ✅ Get search keyword safely
 */
function getSearchKeyword(): string {
    return trim($_GET['q'] ?? '');
}

/**
 * ✅ Load shortlist (view all)
 */
function loadAllShortlist(int $csrId): array {
    $controller = new CSRViewShortlistController();
    return $controller->getShortlistByCSR($csrId);
}

/**
 * ✅ Load shortlist (search by keyword)
 */
function loadSearchedShortlist(int $csrId, string $keyword): array {
    $controller = new CSRSearchShortlistController();
    return $controller->searchShortlist($csrId, $keyword);
}

/**
 * ✅ Render HTML page
 */
function renderShortlistPage(array $shortlist, string $keyword): void {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CSR Shortlist</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4fc; margin: 0; padding: 0; }
  header { background: #4f46e5; color: white; padding: 15px 25px;
           display: flex; justify-content: space-between; align-items: center; }
  header a { color: white; text-decoration: none; font-weight: 600;
             background: rgba(255,255,255,0.15); padding: 6px 12px; border-radius: 8px; }
  header a:hover { background: rgba(255,255,255,0.25); }
  .container { padding: 20px; max-width: 1000px; margin: auto; }
  h1 { color: #4f46e5; }
  .search-box { margin-top: 10px; display: flex; gap: 10px; }
  input[type="text"] { width: 250px; padding: 8px; border: 1px solid #ccc; border-radius: 6px; }
  button.search-btn { background: #4f46e5; color: white; border: none;
                      padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
  button.search-btn:hover { background: #4338ca; }
  table { width: 100%; border-collapse: collapse; background: white;
          border-radius: 8px; overflow: hidden; margin-top: 15px; }
  th, td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; text-align: left; }
  th { background: #eef2ff; color: #4f46e5; }
  tr:hover { background: #f9fafb; }
  .badge { padding: 4px 10px; border-radius: 12px; font-size: 0.8rem; color: white; }
  .badge-open { background: #2563eb; }
  .badge-in_progress { background: #f59e0b; }
  .badge-closed { background: #10b981; }
</style>
</head>
<body>

<header>
  <h2>⭐ My Shortlist</h2>
  <a href="csr_dashboard.php">← Back to Dashboard</a>
</header>

<div class="container">
  <h1>Saved Requests</h1>

  <form method="get" class="search-box">
    <input type="text" name="q" placeholder="Search by title or location"
           value="<?= htmlspecialchars($keyword) ?>">
    <button type="submit" class="search-btn">🔍 Search</button>
  </form>

  <?php if (empty($shortlist)): ?>
    <p>No shortlisted requests<?= $keyword ? ' matching "' . htmlspecialchars($keyword) . '"' : '' ?>.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Location</th>
          <th>Status</th>
          <th>Created</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($shortlist as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['title']) ?></td>
            <td><?= htmlspecialchars($s['location']) ?></td>
            <td><span class="badge badge-<?= strtolower($s['status']) ?>">
              <?= htmlspecialchars(ucfirst($s['status'])) ?></span></td>
            <td><?= htmlspecialchars(date('M d, Y', strtotime($s['created_at']))) ?></td>
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

// ------------------ MAIN EXECUTION FLOW ------------------

authorizeCSR();

$csrId = (int)$_SESSION['user_id'];
$keyword = getSearchKeyword();

// ✅ If keyword provided → search; else → show all
if ($keyword !== '') {
    $shortlist = loadSearchedShortlist($csrId, $keyword);
} else {
    $shortlist = loadAllShortlist($csrId);
}

renderShortlistPage($shortlist, $keyword);
