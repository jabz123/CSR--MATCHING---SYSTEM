<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['profile_type'] ?? '') !== 'pin') {
    header('Location: login.php');
    exit;
}

/* ----------- REQUIRED FILES ----------- */
require_once __DIR__ . '/../Controller/PinCreateRequestController.php';
use App\Controller\PinCreateRequestController;

/* ----------- FUNCTIONS ----------- */

function fetchCategories(): array {
    $ctl = new PinCreateRequestController();
    return $ctl->fetchCategories();
}

function handleFormSubmission(): bool {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
    }

    $title      = trim($_POST['title'] ?? '');
    $content    = trim($_POST['content'] ?? '');
    $location   = trim($_POST['location'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $userId     = (int)($_SESSION['user_id'] ?? 0);

    $errors = [];

    // Validate
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

    // If validation failed
    if ($errors) {
        $_SESSION['flash_type'] = 'error';
        $_SESSION['flash'] = implode('<br>', $errors);
        return false;
    }

    // Try to save
    try {
        $controller = new PinCreateRequestController();
        $ok = $controller->create($userId, $categoryId, $content, $location, $title);

        if ($ok) {
            $_SESSION['flash_type'] = 'success';
            $_SESSION['flash'] = 'Request submitted successfully.';
            header('Location: pin_dashboard.php');
            exit;
        }

        $_SESSION['flash_type'] = 'error';
        $_SESSION['flash'] = 'Saving failed. Please try again.';
        return false;

    } catch (Throwable $t) {
        $_SESSION['flash_type'] = 'error';
        $_SESSION['flash'] = 'Unexpected error: ' . $t->getMessage();
        return false;
    }
}


/* ----------- PAGE EXECUTION ----------- */
$success = handleFormSubmission();
$categories = fetchCategories();
$errors = [];
if (!empty($_SESSION['flash_type']) && $_SESSION['flash_type'] === 'error') {
    $errors = explode('<br>', $_SESSION['flash']);
}

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

    <a href="pin_dashboard.php" class="back-link">Cancel</a>
</div>

</body>
</html>