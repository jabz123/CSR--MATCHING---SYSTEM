<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
header('Content-Type: text/plain; charset=UTF-8');

ini_set('display_errors','0');
ini_set('log_errors','1');
ini_set('error_reporting', (string)E_ALL);
ini_set('error_log', __DIR__ . '/shortlist_debug.log');

/* Catch fatals so body is never empty */
register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo "error=insert_failed\n";
        error_log("[FATAL] {$e['message']} in {$e['file']}:{$e['line']}");
    }
});

session_start();

/** ✅ Validate CSR access */
function validateAccess(): int {
    if (empty($_SESSION['user_id']) || strtolower((string)($_SESSION['profile_type'] ?? '')) !== 'csr') {
        echo "error=unauthorized\n";
        exit;
    }
    return (int)$_SESSION['user_id'];
}

/** ✅ Validate request ID */
function validateRequestId(): int {
    $requestId = (int)($_GET['id'] ?? 0);
    if ($requestId <= 0) {
        echo "error=invalid\n";
        exit;
    }
    return $requestId;
}

/** ✅ Process saving to shortlist */
function processShortlist(int $csrId, int $requestId): void {
    require_once __DIR__ . '/../Controller/CSRSaveShortlistController.php';
    require_once __DIR__ . '/../Entity/shortlistEntity.php';
    require_once __DIR__ . '/../Entity/requestEntity.php';

    $controller = new App\Controller\CSRSaveShortlistController();
    $status = $controller->saveToShortlist($csrId, $requestId);

    if ($status === 'success') {
        echo "success=1\n";
    } elseif ($status === 'duplicate') {
        echo "error=duplicate\n";
    } else {
        echo "error=insert_failed\n";
    }

    error_log("shortlist_add status=$status csr=$csrId req=$requestId");
}

/** 🚀 Main Execution Flow */
$csrId = validateAccess();
$requestId = validateRequestId();
processShortlist($csrId, $requestId);
