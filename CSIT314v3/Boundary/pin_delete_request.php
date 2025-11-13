<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
use App\Controller\PinDeleteRequestController;

session_start();

/* -------------------- FUNCTIONS -------------------- */

function restrictToPIN(): void {
    if (empty($_SESSION['user_id']) || (($_SESSION['profile_type'] ?? '') !== 'pin')) {
        header('Location: login.php');
        exit;
    }
}

function validateRequestMethod(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        setFlash('❌ Invalid access.', 'error');
        header('Location: pin_view_requests.php');
        exit;
    }
}

function validateCSRF(): void {
    if (empty($_POST['_csrf']) || empty($_SESSION['_csrf']) || 
        !hash_equals($_SESSION['_csrf'], (string)$_POST['_csrf'])) {

        setFlash('❌ Invalid submission.', 'error');
        header('Location: pin_view_requests.php');
        exit;
    }
}

function getRequestId(): int {
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        setFlash('❌ Invalid request id.', 'error');
        header('Location: pin_view_requests.php');
        exit;
    }
    return $id;
}

function setFlash(string $message, string $type = 'info'): void {
    $_SESSION['flash'] = $message;
    $_SESSION['flash_type'] = $type;
}

function processDelete(int $requestId): bool {
    require_once __DIR__ . '/../Controller/PinDeleteRequestController.php';
    $ctl = new PinDeleteRequestController();

    try {
        $ok = $ctl->delete((int)$_SESSION['user_id'], $requestId);

        if ($ok) {
            setFlash('✅ Request deleted successfully.', 'success');
            return true;
        }

        setFlash('⚠️ Unable to delete. It may not belong to you or may already be removed.', 'error');
        return false;

    } catch (Throwable $e) {
        setFlash('❌ Error: ' . $e->getMessage(), 'error');
        return false;
    }
}

$deleted = processDelete((int)$_POST['id']);

if ($deleted) {
    header('Location: pin_view_requests.php');
    exit;
} else {
    // Optionally show the error message from flash on the same page
    header('Location: pin_view_requests.php');
    exit;
}


/* -------------------- EXECUTION FLOW -------------------- */

restrictToPIN();

if (empty($_SESSION['_csrf'])) { 
    $_SESSION['_csrf'] = bin2hex(random_bytes(32)); 
}

validateRequestMethod();
validateCSRF();
$requestId = getRequestId();
processDelete($requestId); 