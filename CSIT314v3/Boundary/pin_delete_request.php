<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
use App\Controller\PinDeleteRequestController;

ini_set('display_errors','1'); 
error_reporting(E_ALL);
session_start();

/* -------------------- FUNCTIONS -------------------- */

/** ✅ Ensure only PIN users can access */
function restrictToPIN(): void {
    if (empty($_SESSION['user_id']) || (($_SESSION['profile_type'] ?? '') !== 'pin')) {
        header('Location: login.php');
        exit;
    }
}

/** ✅ Validate request method must be POST */
function validateRequestMethod(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        setFlash('❌ Invalid access.', 'error');
        header('Location: pin_view_requests.php');
        exit;
    }
}

/** ✅ CSRF Token Validation */
function validateCSRF(): void {
    if (empty($_POST['_csrf']) || empty($_SESSION['_csrf']) || 
        !hash_equals($_SESSION['_csrf'], (string)$_POST['_csrf'])) {

        setFlash('❌ Invalid submission.', 'error');
        header('Location: pin_view_requests.php');
        exit;
    }
}

/** ✅ Validate Request ID */
function getRequestId(): int {
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        setFlash('❌ Invalid request id.', 'error');
        header('Location: pin_view_requests.php');
        exit;
    }
    return $id;
}

/** ✅ Set Flash Message */
function setFlash(string $message, string $type = 'info'): void {
    $_SESSION['flash'] = $message;
    $_SESSION['flash_type'] = $type;
}

/** ✅ Perform the deletion using Controller */
function processDelete(int $requestId): void {
    require_once __DIR__ . '/../Controller/PinDeleteRequestController.php';
    $ctl = new PinDeleteRequestController();

    try {
        $ok = $ctl->delete((int)$_SESSION['user_id'], $requestId);

        if ($ok) {
            setFlash('✅ Request deleted successfully.', 'success');
        } else {
            setFlash('⚠️ Unable to delete. It may not belong to you or may already be removed.', 'error');
        }

    } catch (Throwable $e) {
        setFlash('❌ Error: ' . $e->getMessage(), 'error');
    }

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
