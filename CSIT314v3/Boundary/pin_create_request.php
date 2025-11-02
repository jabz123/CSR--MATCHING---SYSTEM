<?php
declare(strict_types=1);
ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);

session_start();

/* Fatal Error Display - keep exactly as you wanted */
register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR])) {
        http_response_code(500);
        echo "<pre style='background:#fee;border:1px solid #f99;padding:12px'>";
        echo "Fatal Error: {$e['message']} in {$e['file']}:{$e['line']}";
        echo "</pre>";
    }
});

/* Access Control */
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['profile_type'] ?? '') !== 'pin') {
    header('Location: login.php');
    exit;
}

/* ----------- REQUIRED FILES ----------- */
require_once __DIR__ . '/../Controller/PinCreateRequestController.php';
require_once __DIR__ . '/../Entity/requestEntity.php';

use App\Controller\PinCreateRequestController;
use App\Entity\requestEntity;

/* ----------- FUNCTIONS ----------- */

/** Fetch categories using Entity */
function fetchCategories(): array {
    $entity = new requestEntity();
    return $entity->getCategories();  // ✅ now using your new entity function
}

/** Validate + Process Form Submission */
function handleFormSubmission(): array {
    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return [$success, $errors];
    }

    // ✅ Trim & Validate here (Boundary responsibility)
    $title      = trim($_POST['title'] ?? '');
    $content    = trim($_POST['content'] ?? '');
    $location   = trim($_POST['location'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $userId     = (int)($_SESSION['user_id'] ?? 0);

    if ($title === '' || strlen($title) > 255) {
        $errors[] = 'Title is required (max 255 chars).';
    }
    if ($location === '' || strlen($location) > 255) {
        $errors[] = 'Location is required (max 255 chars).';
    }
    if ($content === '' || strlen($content) > 4000) {
        $errors[] = 'Description required (max 4000 chars).';
    }
    if ($categoryId <= 0) {
        $errors[] = 'Please select a category.';
    }

    if ($errors) {
        return [$success, $errors];
    }

    // ✅ If valid → call controller only
    try {
        $controller = new PinCreateRequestController();
        $ok = $controller->create($userId, $categoryId, $content, $location, $title);

        if ($ok) {
            $_SESSION['flash'] = 'Request submitted successfully.';
            header('Location: pin_dashboard.php');
            exit;
        }

        $errors[] = 'Saving failed. Please try again.';
    } catch (Throwable $t) {
        $errors[] = 'Unexpected error: '.$t->getMessage();
    }

    return [$success, $errors];
}

/* ----------- PAGE EXECUTION ----------- */
[$success, $errors] = handleFormSubmission();
$categories = fetchCategories();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Create Help Request</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ece8ff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 40px auto;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; margin-bottom: 20px; color: #4b0082; }
        label { font-weight: bold; display: block; margin-top: 12px; }
        input, textarea, select {
            width: 100%; padding: 10px; margin-top: 6px; border-radius: 5px;
            border: 1px solid #aaa; box-sizing: border-box;
        }
        button {
            width: 100%; padding: 12px; margin-top: 20px;
            background-color: #4b0082; color: white; border: none; border-radius: 5px;
            cursor: pointer; font-size: 16px;
        }
        button:hover { background-color: #360061; }
        .msg-error { color: red; margin-top: 10px; text-align: center; font-weight: bold; }
        .back-link { display: block; text-align: center; margin-top: 15px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Create a Help Request</h2>

    <?php if (!empty($errors)): ?>
        <p class="msg-error"><?= htmlspecialchars(implode("<br>", $errors)) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>">
                    <?= htmlspecialchars($cat['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Title</label>
        <input type="text" name="title" required maxlength="255"/>

        <label>Location</label>
        <input type="text" name="location" required maxlength="255"/>

        <label>Describe what help you need</label>
        <textarea name="content" rows="5" required maxlength="4000"></textarea>

        <button type="submit">Submit Request</button>
    </form>

    <a href="pin_dashboard.php" class="back-link">⬅ Back to Dashboard</a>
</div>

</body>
</html>
