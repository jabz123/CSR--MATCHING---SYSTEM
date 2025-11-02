<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../Controller/suspendAccController.php';
use App\Controller\suspendAccController;

/* ----------------------------------------------------
   ✅ Context-Relevant Helper Functions (Suspend Feature)
   (No behaviour changes — only centralizing boundary tasks)
---------------------------------------------------- */

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

/* ----------------------------------------------------
   Original Code (Retained exactly as you wrote)
---------------------------------------------------- */

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

$ctl = new suspendAccController();
$ok  = false;
$why = null;

try {
    $ok = $ctl->suspendUser((int)$targetId, $sFlag);
    if (!$ok) {
        require_once __DIR__ . '/../Entity/userAccount.php';
        $u = \App\Entity\userAccount::getUserById((int)$targetId);
        $why = $u ? 'db_update_failed' : 'no_such_id';
    }
} catch (\Throwable $e) {
    $why = 'exception:' . $e->getMessage();
}

$code = $ok ? ($sFlag ? 'suspended' : 'activated') : 'failed';
$q = [
    'notice' => $code,
    'id'     => (string)$targetId,
];
if (!$ok && $why) {
    $q['why'] = $why;
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
  <p><?= htmlspecialchars($ok ? 'Success' : 'Failed') ?> (<?= htmlspecialchars($code) ?><?= isset($q['why']) ? ', ' . htmlspecialchars($q['why']) : '' ?>)</p>
  <p><a href="<?= htmlspecialchars($returnUrl . '?' . http_build_query($q)) ?>">Back to list</a></p>
</body>
</html>
    