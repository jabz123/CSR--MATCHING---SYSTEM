<?php
declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
header('Content-Type: text/plain; charset=UTF-8');

session_start();

/** Validate CSR access */
function validateAccess(): int {
    if (empty($_SESSION['user_id']) || strtolower((string)($_SESSION['profile_type'] ?? '')) !== 'csr') {
        echo "error=unauthorized\n";
        exit;
    }
    return (int)$_SESSION['user_id'];
}

/** Validate request ID */
function validateRequestId(): int {
    $requestId = (int)($_GET['id'] ?? 0);
    if ($requestId <= 0) {
        echo "error=invalid\n";
        exit;
    }
    return $requestId;
}

/** Process saving to shortlist */
function processShortlist(int $csrId, int $requestId): void {
    require_once __DIR__ . '/../Controller/CSRSaveShortlistController.php';

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
