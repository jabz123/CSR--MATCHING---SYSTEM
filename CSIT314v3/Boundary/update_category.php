<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
session_start();

use App\Controller\PMUpdateCategoryController;
require_once dirname(__DIR__) . '/Controller/PMUpdateCategoryController.php';

/* ============================================================
   🔧 Helper Functions
============================================================ */

/** Get category ID from query string */
function getCategoryId(): int {
    return isset($_GET['id']) ? (int)$_GET['id'] : 0;
}

/** Main helper: performs trim, validation, update, returns alert or null */
function updateCategoryIfSubmitted(PMUpdateCategoryController $controller, int $id): ?string {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return null; // form not submitted
    }

    $newName = trim((string)($_POST['category_name'] ?? ''));

    if ($newName === '') {
        return 'Category name cannot be empty.';
    }

    if (mb_strlen($newName) > 100) {
        return 'Category name must be under 100 characters.';
    }

    $ok = $controller->updateCategory($id, $newName);

    if ($ok) {
        header('Location: pm_viewcategorypg.php?msg=updated');
        exit;
    }

    return 'Failed to update category. Try again.';
}

/* ============================================================
   ✅ Original Boundary Code (Retained)
============================================================ */

// Access control
if (
    !isset($_SESSION['profile_type']) ||
    !in_array(strtolower(trim($_SESSION['profile_type'])), ['platform', 'pm'])
) {
    header('Location: ../login.php');
    exit;
}

$controller = new PMUpdateCategoryController();

$id = getCategoryId();
$category = $controller->getCategory($id);

if (!$category) {
    header('Location: pm_viewcategorypg.php');
    exit;
}

$alert = updateCategoryIfSubmitted($controller, $id); // ← now clean
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Update Category</title>
<style>
/* (Your CSS retained exactly) */
body {
  font-family: "Poppins", sans-serif;
  background: linear-gradient(135deg,#d7e2ff,#e5d9ff,#f1e7ff);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}
.container {
  background: white;
  padding: 40px;
  border-radius: 20px;
  width: 90%;
  max-width: 500px;
  text-align: center;
  box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}
h1 {
  color: #6b4bd1;
  margin-bottom: 20px;
}
input[type="text"] {
  width: 100%;
  padding: 12px;
  margin-bottom: 20px;
  border: 1px solid #ccc;
  border-radius: 10px;
  font-size: 16px;
}
.btn {
  padding: 10px 25px;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  font-weight: 600;
  color: white;
  background: linear-gradient(90deg, #7c5ce0, #9b7df2);
  transition: 0.3s ease;
}
.btn:hover {
  background: linear-gradient(90deg, #6a4bc4, #8c6cd8);
}
.alert {
  color: red;
  margin-bottom: 10px;
}
</style>
</head>
<body>
  <div class="container">
    <h1>Update Category</h1>

    <?php if ($alert): ?>
      <div class="alert"><?= htmlspecialchars($alert, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="category_name"
             value="<?= htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8') ?>"
             maxlength="100" required>
      <br>
      <button type="submit" class="btn">Save Changes</button>
      <a href="pm_viewcategorypg.php" class="btn" style="background:#ff6b6b;margin-left:10px;">Cancel</a>
    </form>
  </div>
</body>
</html>
