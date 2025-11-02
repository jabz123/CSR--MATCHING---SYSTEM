<?php
declare(strict_types=1);
session_start();

use App\Controller\PMCreateCategoryController;
use App\Controller\PMDeleteCategoryController;
use App\Controller\PMSearchCategoryController;

require_once __DIR__ . '/../Controller/pm_createcontrollerpage.php';
require_once __DIR__ . '/../Controller/PMDeleteCategoryController.php';
require_once __DIR__ . '/../Controller/PMSearchCategoryController.php';

// ✅ Access Control
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['profile_type']) ||
    !in_array(strtolower(trim((string)$_SESSION['profile_type'])), ['platform', 'pm'], true)
) {
    header('Location: ../login.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username'] ?? 'Platform Manager', ENT_QUOTES, 'UTF-8');

$alertType = '';
$alertMsg  = '';

// Instantiate controllers (used by functions via `global`)
$createController = new PMCreateCategoryController();
$deleteController = new PMDeleteCategoryController();
$searchController = new PMSearchCategoryController();

/* ============================================================
   FUNCTIONS (Boundary helpers; keep SQL out of boundary)
   ============================================================ */

/**
 * Load categories for display (uses Controller, no SQL here)
 */
function loadCategories(string $searchTerm = ''): array {
    $searchTerm = trim($searchTerm);
    global $searchController;
    return $searchController->searchCategories($searchTerm);
}

/**
 * Handle creation of a category (input trimmed & validated here)
 * Returns [alertType, alertMsg, searchTermAfterCreate]
 *   - With Option A behavior (Reset Search): search term becomes ''
 */
function handleCreateCategory(): array {
    global $createController;

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || ($_POST['action'] ?? '') !== 'add_category') {
        return ['', '', null]; // [alertType, alertMsg, searchTermAfter]
    }

    $name = trim((string)($_POST['category_name'] ?? ''));

    if ($name === '') {
        return ['error', 'Category name cannot be empty.', null];
    }

    if (mb_strlen($name) > 100) {
        return ['error', 'Category name must be 100 characters or fewer.', null];
    }

    try {
        $ok = $createController->createCategory($name);
        if ($ok) {
            // ✅ Option A: Reset search so full list shows after add
            return ['success', 'Category added successfully.', ''];
        }
        return ['error', 'Category already exists.', null];
    } catch (Throwable $t) {
        return ['error', 'Unexpected error: ' . htmlspecialchars($t->getMessage(), ENT_QUOTES, 'UTF-8'), null];
    }
}

/**
 * Handle deletion of a category (AJAX request only)
 * Echoes JSON and exits if matched.
 */
function handleDeleteCategory(): void {
    global $deleteController;

    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['action'] ?? '') === 'delete_category') {
        $deleteId = (int)($_POST['id'] ?? 0);
        $deleted = $deleteController->deleteCategory($deleteId);
        echo json_encode(['success' => $deleted]);
        exit;
    }
}

/* ============================================================
   ACTION HANDLERS (use functions above)
   ============================================================ */

// Handle AJAX delete (returns JSON then exits)
handleDeleteCategory();

// Handle Add Category (Option A: reset search on success)
[$alertType, $alertMsg, $searchAfter] = handleCreateCategory();

// Handle Search Input (default empty, but override with reset if created)
$searchTerm = trim((string)($_GET['search'] ?? ''));
if ($searchAfter !== null) {
    // If handleCreateCategory ran, respect its suggested search term ('' for Option A)
    $searchTerm = $searchAfter;
}

// Fetch categories via controller
$categories = loadCategories($searchTerm);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Service Categories</title>
<style>
  :root{
    --bg1:#dfe7ff; --bg2:#efdfff;
    --glass:rgba(255,255,255,.28);
    --card:rgba(255,255,255,.70);
    --text:#2c2750;
    --accent:#7c5ce0; --accent2:#9b7df2;
    --danger:#ef5a5a; --danger2:#d94848;
    --shadow:0 18px 40px rgba(39,15,102,.14);
    --radius:26px;
  }
  *{box-sizing:border-box}
  body{
    margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center;
    font-family:"Poppins",system-ui,-apple-system,Segoe UI,Roboto,Arial;
    color:var(--text);
    background:radial-gradient(1200px 900px at 50% -10%, var(--bg2), var(--bg1));
  }
  .page{display:flex; flex-direction:column; align-items:center; gap:26px; width:100%; padding:30px 0 40px;}
  .hero, .card{width:min(900px,92vw);}
  .hero{
    background:var(--card); backdrop-filter:blur(14px);
    border-radius:var(--radius); box-shadow:var(--shadow);
    padding:clamp(22px,4vw,36px); text-align:center;
  }
  .hero-title{margin:0 0 6px; font-size:clamp(22px,3vw,34px); color:var(--accent);}
  .hero-sub{margin:0; opacity:.9; font-size:clamp(13px,2.2vw,16px);}
  .hero-actions{display:flex; gap:12px; justify-content:center; flex-wrap:wrap; margin-top:16px;}
  .btn{
    border:0; cursor:pointer; border-radius:999px; padding:12px 20px;
    font-weight:700; letter-spacing:.2px; color:#fff; text-decoration:none;
    display:inline-flex; align-items:center; justify-content:center;
    transition:transform .05s ease, box-shadow .15s ease, background-color .15s ease;
    user-select:none; min-width:160px;
  }
  .btn:active{transform:translateY(1px)}
  .btn-ghost{color:var(--accent); background:#fff; border:1px solid rgba(124,92,224,.28); box-shadow:0 8px 16px rgba(124,92,224,.10);}
  .btn-primary{background:linear-gradient(90deg,var(--accent),var(--accent2)); box-shadow:0 10px 22px rgba(124,92,224,.24);}
  .btn-danger{background:linear-gradient(90deg,var(--danger),var(--danger2)); box-shadow:0 10px 22px rgba(239,90,90,.24);}
  .alert{width:min(900px,92vw); border-radius:16px; margin:0 auto; padding:12px 14px; font-size:14px;
    background:rgba(255,255,255,.66); border:1px solid rgba(0,0,0,.06); box-shadow:0 10px 20px rgba(0,0,0,.05);}
  .alert.success{color:#145a32; border-color:rgba(46,204,113,.4); background:rgba(46,204,113,.12);}
  .alert.error{color:#7b241c; border-color:rgba(231,76,60,.4); background:rgba(231,76,60,.12);}
  .card{background:rgba(255,255,255,.68); backdrop-filter:blur(12px);
    border:1px solid rgba(124,92,224,.14); border-radius:20px; box-shadow:var(--shadow); overflow:hidden;}
  .card-header{display:flex; align-items:center; justify-content:space-between; gap:10px;
    padding:16px 18px; background:rgba(124,92,224,.08); color:#553cb4;}
  .card-title{margin:0; font-size:16px; font-weight:800;}
  table{width:100%; border-collapse:collapse;}
  th, td{padding:14px 16px; text-align:left; font-size:15px;}
  thead th{background:rgba(124,92,224,.08); color:#563eb8; font-weight:800;}
  tbody tr:not(:last-child) td{border-bottom:1px solid rgba(0,0,0,.06);}
  .action-group{display:flex; align-items:center; gap:8px;}
  .action-btn{padding:6px 14px; border-radius:8px; font-size:13px; font-weight:600; color:white;
    text-decoration:none; transition:transform .1s ease, opacity .2s ease;}
  .action-btn:hover{transform:translateY(-1px); opacity:.9;}
  .update-btn{background:linear-gradient(135deg,#a78bfa,#7c3aed);}
  .delete-btn{background:linear-gradient(135deg,#ef5a5a,#d94848);}
  .modal-backdrop{
    position:fixed; inset:0; display:none; align-items:center; justify-content:center;
    background:rgba(30,18,71,.25); backdrop-filter:blur(4px); z-index:50;
  }
  .modal{
    width:min(520px,92vw); background:var(--glass); backdrop-filter:blur(16px);
    border-radius:24px; border:1px solid rgba(255,255,255,.45);
    box-shadow:0 24px 60px rgba(39,15,102,.25); padding:22px; animation:fadeIn .2s ease;
  }
  @keyframes fadeIn{from{opacity:0;transform:translateY(-10px);}to{opacity:1;transform:translateY(0);}}
  .modal h3{margin:0 0 6px; color:var(--accent);}
  .modal p{margin:0 0 14px; opacity:.9;}
  .modal-actions{display:flex; gap:10px; justify-content:flex-end; margin-top:8px;}
</style>
</head>
<body>
  <div class="page">
    <section class="hero">
      <h1 class="hero-title">Service Categories</h1>
      <p class="hero-sub">Welcome, <strong><?= $username ?></strong></p>
      <div class="hero-actions">
        <a class="btn btn-ghost" href="pm_dashboard.php">⬅ Back</a>
        <button class="btn btn-primary" id="openAddModal">+ Add Category</button>
        <a class="btn btn-danger" href="pm_dashboard.php?action=logout">Logout</a>
      </div>
    </section>

    <?php if ($alertType && $alertMsg): ?>
      <div class="alert <?= $alertType === 'success' ? 'success' : 'error' ?>">
        <?= htmlspecialchars($alertMsg, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <section class="card">
      <div class="card-header">
        <h3 class="card-title">All Categories</h3>
        <form method="get" style="display:flex; align-items:center; gap:8px;">
          <input type="text" name="search" value="<?= htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8') ?>"
                 placeholder="Search categories..."
                 style="padding:8px 12px; border-radius:8px; border:1px solid rgba(124,92,224,.4);
                 background:rgba(255,255,255,.9); font-size:14px;">
          <button type="submit" class="btn btn-primary" style="min-width:auto; padding:8px 14px;">Search</button>
          <a href="pm_viewcategorypg.php" class="btn btn-ghost" style="min-width:auto; padding:8px 14px;">Clear</a>
        </form>
        <small>Showing <?= count($categories) ?> item(s)</small>
      </div>

      <table>
        <thead>
          <tr><th>ID</th><th>Category Name</th><th>Created</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php if (empty($categories)): ?>
            <tr><td colspan="4" style="opacity:.7">No categories yet. Click “Add Category”.</td></tr>
          <?php else: foreach ($categories as $c): ?>
            <tr id="row-<?= (int)$c['category_id'] ?>">
              <td><?= (int)$c['category_id'] ?></td>
              <td><?= htmlspecialchars((string)$c['category_name'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string)$c['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
              <td>
                <div class="action-group">
                  <a href="update_category.php?id=<?= (int)$c['category_id'] ?>" class="action-btn update-btn">Update</a>
                  <a href="#" class="action-btn delete-btn"
                     data-id="<?= (int)$c['category_id'] ?>"
                     data-name="<?= htmlspecialchars((string)$c['category_name'], ENT_QUOTES, 'UTF-8') ?>"
                     onclick="openDeleteModal(event,this)">Delete</a>
                </div>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </section>
  </div>

  <!-- Add Modal -->
  <div class="modal-backdrop" id="modalBackdrop">
    <div class="modal">
      <h3>Add New Category</h3>
      <p>Enter a unique category name (max 100 characters).</p>
      <form method="POST" onsubmit="return handleTrim();">
        <input type="hidden" name="action" value="add_category" />
        <input class="input" type="text" name="category_name" id="category_name"
               placeholder="e.g., Financial Aid" maxlength="100" required />
        <div class="modal-actions">
          <button type="button" class="btn btn-ghost" id="closeAddModal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal-backdrop" id="deleteModal">
    <div class="modal">
      <h3 style="color:var(--danger)">Delete Category</h3>
      <p id="deleteMessage">Are you sure you want to delete this category?</p>
      <div class="modal-actions">
        <button type="button" class="btn btn-ghost" id="cancelDelete">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>

<script>
  const openBtn=document.getElementById('openAddModal');
  const closeBtn=document.getElementById('closeAddModal');
  const backdrop=document.getElementById('modalBackdrop');
  openBtn?.addEventListener('click',()=>backdrop.style.display='flex');
  closeBtn?.addEventListener('click',()=>backdrop.style.display='none');
  backdrop?.addEventListener('click',(e)=>{if(e.target===backdrop)backdrop.style.display='none';});

  function handleTrim(){
    const input=document.getElementById('category_name');
    input.value=input.value.trim();
    if(input.value.length===0){alert('Category name cannot be empty.');return false;}
    if(input.value.length>100){alert('Category name must be 100 characters or fewer.');return false;}
    return true;
  }

  let deleteId=null;
  function openDeleteModal(e,el){
    e.preventDefault();
    deleteId=el.dataset.id;
    const name=el.dataset.name;
    document.getElementById('deleteMessage').textContent=`Are you sure you want to delete "${name}"?`;
    document.getElementById('deleteModal').style.display='flex';
  }
  document.getElementById('cancelDelete').addEventListener('click',()=>document.getElementById('deleteModal').style.display='none');

  document.getElementById('confirmDelete').addEventListener('click',()=>{
    if(!deleteId)return;
    fetch(window.location.href,{
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:`action=delete_category&id=${deleteId}`
    })
    .then(res=>res.json())
    .then(data=>{
      if(data.success){
        document.getElementById('row-'+deleteId)?.remove();
        document.getElementById('deleteModal').style.display='none';
        alert('Category deleted successfully.');
      } else {
        alert('Failed to delete category.');
      }
    })
    .catch(()=>alert('Error deleting category.'));
  });
</script>
</body>
</html>
