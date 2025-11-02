<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

use App\Controller\UpdateProfileController;
require_once __DIR__ . '/../Controller/UpdateProfileController.php';

/* ==============================================
   ✅ Helper Functions (No behavior change)
   These keep boundary clean & focused
================================================ */

/** Get and validate profile ID */
function getProfileId(): int {
    return isset($_GET['id']) ? (int)$_GET['id'] : 0;
}

/** Fetch profile or return null */
function fetchProfile(UpdateProfileController $controller, int $id): ?array {
    return $controller->getProfileById($id);
}

/** If submitted, process update and return error message or null */
function updateProfileIfSubmitted(UpdateProfileController $controller, int $id): ?string {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        return null; // form not submitted
    }

    $newType = trim((string)($_POST['profile_type'] ?? ''));
    $status  = (string)($_POST['status'] ?? '');

    // Basic Boundary validations
    if ($newType === '') {
        return "Profile type cannot be empty.";
    }

    if (!in_array($status, ['active', 'suspended'], true)) {
        return "Invalid status option selected.";
    }

    // Call Controller
    $ok = $controller->updateProfile($id, $newType, $status);

    if ($ok) {
        header("Location: view_profiles.php?update_success=1");
        exit();
    }

    return "Failed to update profile.";
}

/* ==============================================
   ✅ Original Code (unchanged)
================================================ */

$id = getProfileId();
if (!$id) {
    die("No profile ID provided.");
}

$controller = new UpdateProfileController();
$profile = fetchProfile($controller, $id);

if (!$profile) {
    die("Profile not found.");
}

$error = updateProfileIfSubmitted($controller, $id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: 600;
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        button {
            background: #6366f1;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
        }
        button:hover {
            background: #4f46e5;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #6366f1;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Update Profile</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;text-align:center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="profile_type">Profile Type</label>
        <input type="text" id="profile_type" name="profile_type"
               value="<?= htmlspecialchars($profile['profile_type']) ?>" required>

        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="active" <?= $profile['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="suspended" <?= $profile['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
        </select>

        <button type="submit">Save Changes</button>
    </form>

    <a href="view_profiles.php" class="back-link">⬅ Back to Profiles</a>
</div>
</body>
</html>
