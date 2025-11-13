<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../Controller/suspendAccController.php';
use App\Controller\suspendAccController;

/** Ensure user is logged in & an admin */
function require_admin_access(string $returnUrl): void {
    if (!isset($_SESSION['user_id'])) {
        redirect_to('login.php');
    }

    if (($_SESSION['profile_type'] ?? '') !== 'admin') {
        redirect_to($returnUrl . '?notice=forbidden');
    }
}

/** Fetch and validate the target user ID for suspension */
function get_target_user_id(): ?int {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    return $id !== false ? $id : null;
}

/** Returns true if request is asking to suspend action */
function is_suspend_action(): bool {
    return (($_GET['action'] ?? '') === 'suspend');
}

/** True = suspend, False = activate */
function get_suspend_flag(): bool {
    return (($_GET['s'] ?? '') === '1');
}

/** Prevent a user from suspending themselves */
function is_self_suspend(int $targetId): bool {
    return $targetId === (int)($_SESSION['user_id'] ?? 0);
}

/** Safe redirect */
function redirect_to(string $url): void {
    if (!headers_sent()) {
        header('Location: ' . $url);
    }
    exit;
}

/** Suspend or activate a user - returns true for success, false for failure */
function suspend_user(int $targetId, bool $sFlag): bool {
    $ctl = new suspendAccController();
    
    try {
        // Call suspendUser from the controller
        $ok = $ctl->suspendUser($targetId, $sFlag);
        
        // If the action failed, fetch user info from the Entity layer
        if (!$ok) {
            $u = $ctl->getUserById($targetId); // Using the entity to get user details
            return false;  // Return false if the user suspension/activation failed
        }
    } catch (\Throwable $e) {
        return false;  // Return false if an exception occurred
    }

    return true;  // Return true if suspension/activation was successful
}

$returnUrl = 'view_users.php';

require_admin_access($returnUrl);

$targetId = get_target_user_id();
$action   = $_GET['action'] ?? '';
$sFlag    = get_suspend_flag();

if (!is_suspend_action() || !$targetId) {
    redirect_to($returnUrl . '?notice=invalid');
}

if (is_self_suspend($targetId)) {
    redirect_to($returnUrl . '?notice=self');
}

// Use the function to suspend or activate the user and handle the result
$ok = suspend_user($targetId, $sFlag);

// Redirect with appropriate notice based on success or failure
$q = [
    'notice' => $ok ? ($sFlag ? 'suspended' : 'activated') : 'failed',
    'id'     => (string)$targetId,
];

if (!$ok) {
    $q['why'] = 'db_update_failed'; // You can add more detailed failure reasons if needed
}

if (!headers_sent()) {
    header('Location: ' . $returnUrl . '?' . http_build_query($q));
    exit;
}
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>Redirecting…</title></head>
<body>
  <p><?= htmlspecialchars($ok ? 'Success' : 'Failed') ?> (<?= htmlspecialchars($q['notice']) ?>)</p>
  <p><a href="<?= htmlspecialchars($returnUrl . '?' . http_build_query($q)) ?>">Back to list</a></p>
</body>
</html>
