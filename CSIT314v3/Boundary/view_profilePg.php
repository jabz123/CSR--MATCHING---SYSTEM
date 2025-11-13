<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
use App\Controller\ViewProfileDetailsController;
require_once __DIR__ . '/../Controller/ViewProfileDetailsController.php';


/** Validate and return profile ID */
function getProfileId(): int {
    if (!isset($_GET['id'])) {
        die('Profile ID not specified.');
    }
    return (int)($_GET['id'] ?? 0);
}

/** Fetch profile details using controller */
function fetchProfile(ViewProfileDetailsController $controller, int $id): ?array {
    return $controller->getProfileDetails($id);
}

/** If profile not found, stop page */
function ensureProfileExists(?array $profile): void {
    if (!$profile) {
        die('Profile not found.');
    }
}

/* ============================================================
   ✅ Main Flow (Procedural)
============================================================ */

$controller = new ViewProfileDetailsController();

$id = getProfileId();
$profile = fetchProfile($controller, $id);
ensureProfileExists($profile);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Profile Details</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #333;
            padding: 40px;
        }
        .container {
            background: #fff;
            border-radius: 16px;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #4f46e5;
        }
        .info {
            margin-top: 20px;
            line-height: 1.8;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profile Details</h2>
        <div class="info">
            <p><strong>ID:</strong> <?= htmlspecialchars((string)$profile['id']) ?></p>
            <p><strong>Profile Type:</strong> <?= htmlspecialchars($profile['profile_type']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($profile['status']) ?></p>
            <p><strong>Created At:</strong> <?= htmlspecialchars($profile['created_at']) ?></p>
        </div>
        <a href="view_profiles.php">← Back to Profiles</a>
    </div>
</body>
</html>
