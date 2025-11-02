<?php
declare(strict_types=1);
ini_set('display_errors','1'); 
error_reporting(E_ALL);

use App\Controller\PinUpdateRequestController;
use App\Entity\requestEntity;

session_start();

/* ---------------- FUNCTIONS ---------------- */

function restrictToPIN(): void {
    if (empty($_SESSION['user_id']) || (($_SESSION['profile_type'] ?? '') !== 'pin')) {
        header('Location: login.php');
        exit;
    }
}

function initCSRF(): void {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
}

function loadRequest(int $id): array {
    require_once __DIR__ . '/../Controller/PinUpdateRequestController.php';
    $ctl = new PinUpdateRequestController();
    $item = $ctl->get((int)$_SESSION['user_id'], $id);

    if (!$item) {
        $_SESSION['flash_edit_request'] = 'Request not found.';
        header('Location: pin_view_requests.php');
        exit;
    }
    return $item;
}

/* ✅ Load categories using ENTITY (No SQL here anymore) */
function loadCategories(): array {
    require_once __DIR__ . '/../Entity/requestEntity.php';
    $entity = new requestEntity();
    return $entity->getCategories();
}

function validateForm(array &$item, array &$errors): void {
    $csrfOk = !empty($_POST['_csrf']) && hash_equals($_SESSION['_csrf'], (string)$_POST['_csrf']);
    if (!$csrfOk) {
        $errors[] = 'Invalid submission. Please try again.';
        return;
    }

    $item['category_id'] = (int)($_POST['category_id'] ?? 0);
    $item['content']     = trim((string)($_POST['content'] ?? ''));
    $item['location']    = trim((string)($_POST['location'] ?? ''));
    $item['title']       = trim((string)($_POST['title'] ?? ''));

    if ($item['category_id'] <= 0) $errors[] = 'Please select a category.';
    if ($item['content'] === '' || mb_strlen($item['content']) > 4000)  $errors[] = 'Content required (max 4000).';
    if ($item['location'] === '' || mb_strlen($item['location']) > 255) $errors[] = 'Location required (max 255).';
    if ($item['title'] === '' || mb_strlen($item['title']) > 255)       $errors[] = 'Title required (max 255).';
}

function processUpdate(int $id, array $item, array &$errors): void {
    require_once __DIR__ . '/../Controller/PinUpdateRequestController.php';
    $ctl = new PinUpdateRequestController();

    if (!$errors) {
        $ok = $ctl->update((int)$_SESSION['user_id'], $id, $item['category_id'], $item['content'], $item['location'], $item['title']);
        if ($ok) {
            $_SESSION['flash_edit_request'] = '✅ Request updated.';
            header('Location: pin_view_requests.php');
            exit;
        }
        $errors[] = 'Update failed.';
    }
}

/* ---------------- EXECUTION FLOW ---------------- */

restrictToPIN();
initCSRF();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['flash_edit_request'] = 'Invalid request id.';
    header('Location: pin_view_requests.php');
    exit;
}

$item = loadRequest($id);
$categories = loadCategories();
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    validateForm($item, $errors);
    processUpdate($id, $item, $errors);
}

$userName = htmlspecialchars($_SESSION['name'] ?? 'pin');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Request</title>
<style>
  html,body{min-height:100vh} html{background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 50%,#d946ef 100%);background-attachment:fixed}
  body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:#111827;background:transparent}
  .wrap{max-width:1100px;margin:28px auto;padding:0 18px}
  .topbar{background:rgba(255,255,255,.95);backdrop-filter:blur(8px);border-radius:20px;padding:18px 22px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 10px 35px rgba(0,0,0,.15)}  
  .title {font-weight:800; color:#4f46e5; font-size:22px; }
  .hero{margin:26px 0;background:#fff;border-radius:20px;padding:40px;box-shadow:0 12px 40px rgba(0,0,0,.18)}
  label{display:block;margin:12px 0 6px;font-weight:700}
  input[type=text],textarea,select{width:100%;padding:12px;border:1px solid #e5e7eb;border-radius:10px;font:inherit}
  textarea{min-height:140px}
  .row{display:flex;gap:10px;margin-top:16px}
  .btn{padding:12px 16px;border-radius:10px;border:0;cursor:pointer;font-weight:800;color:#fff;background:linear-gradient(135deg,#6366f1,#8b5cf6)}
  .btn-secondary{padding:10px 16px; border:0; border-radius:12px; background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; cursor:pointer; font-weight:800; font-size:14px; }
  .errors{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:10px 12px;border-radius:10px;margin-bottom:12px}
</style>
</head>
<body>
<div class="wrap">
  <div class="topbar">
    <div class="title">Edit Request</div>
    <div>Welcome, <strong><?= $userName ?></strong>
      <a href="pin_view_requests.php"><button class="btn-secondary">Back</button></a>
    </div>
  </div>

  <div class="hero">
    <?php if ($errors): ?>
      <div class="errors"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
    <?php endif; ?>

    <form method="post" action="pin_edit_request.php?id=<?= (int)$item['id'] ?>">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['_csrf']) ?>">

      <!-- ✅ Category Dropdown -->
      <label for="category_id">Category</label>
      <select name="category_id" id="category_id" required>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['category_id'] ?>" <?= ($cat['category_id'] == $item['category_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['category_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Title</label>
      <input type="text" name="title" maxlength="255" value="<?= htmlspecialchars($item['title']) ?>" required>

      <label>Location</label>
      <input type="text" name="location" maxlength="255" value="<?= htmlspecialchars($item['location']) ?>" required>

      <label>Request Details</label>
      <textarea name="content" maxlength="4000" required><?= htmlspecialchars($item['content']) ?></textarea>

      <div class="row">
        <button type="submit" class="btn">Save Changes</button>
        <a href="pin_view_requests.php" class="btn btn-secondary" style="text-decoration:none;display:inline-block;padding:12px 16px;">Cancel</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
