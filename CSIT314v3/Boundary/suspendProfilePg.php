<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

use App\Controller\SuspendProfileController;
require_once __DIR__ . '/../Controller/suspendProfileController.php';


/** Validate profile ID */
function get_profile_id(): ?int {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    return $id !== false ? $id : null;
}

/** Normalize action to suspend/activate */
function get_valid_action(): string {
    $action = strtolower(trim($_GET['action'] ?? ''));
    return in_array($action, ['suspend', 'activate'], true) ? $action : '';
}

/** Prepare UI message + status */
function build_status_message(string $action, bool $success): array {
    if ($success) {
        return [
            'status'  => ($action === 'suspend') ? 'Suspended' : 'Active',
            'message' => ($action === 'suspend')
                ? 'Profile has been suspended successfully.'
                : 'Profile has been activated successfully.'
        ];
    }

    return [
        'status'  => 'Error',
        'message' => 'Failed to update profile status.'
    ];
}

// Validate request parameters
$id = get_profile_id();
$action = get_valid_action();

if (!$id || $action === '') {
    die('⚠️ Invalid request. Please go back and try again.');
}

// Instantiate Controller
$controller = new SuspendProfileController();

// Perform suspend/activate
$success = $controller->handleSuspendAction($id, $action);

// Build output
$result = build_status_message($action, $success);
$status = $result['status'];
$message = $result['message'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= ucfirst($action) ?> Profile</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }
    .card {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        width: 400px;
        animation: fadeIn 0.6s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    h2 {
        margin-bottom: 20px;
        color: #4f46e5;
    }
    p {
        font-size: 1.1rem;
        margin-bottom: 30px;
    }
    .btn {
        display: inline-block;
        padding: 10px 20px;
        margin: 0 10px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-back {
        background: linear-gradient(135deg, #14b8a6, #0d9488);
        color: white;
    }
    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(20, 184, 166, 0.4);
    }
    .btn-dashboard {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
    }
    .btn-dashboard:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(99, 102, 241, 0.4);
    }
    .status {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 15px;
    }
    .status.suspended { color: #dc2626; }
    .status.active { color: #16a34a; }
    .status.error { color: #b91c1c; }
</style>
</head>
<body>

<div class="card">
    <h2><?= ucfirst($action) ?> Profile</h2>
    <p class="status <?= strtolower($status) ?>">Status: <?= htmlspecialchars($status) ?></p>
    <p><?= htmlspecialchars($message) ?></p>
    <div>
        <a href="view_profiles.php" class="btn btn-back">← Back to Profiles</a>
        <a href="admin_dashboard.php" class="btn btn-dashboard">🏠 Dashboard</a>
    </div>
</div>

</body>
</html>
