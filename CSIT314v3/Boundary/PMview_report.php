<?php
declare(strict_types=1);

namespace App\Boundary;

// Import controller classes
use App\Controller\PMWeeklyReportController;
use App\Controller\PMMonthlyReportController;
use App\Controller\PMDailyReportController;

require_once __DIR__ . '/../bootstrap.php';
// Manual includes
require_once __DIR__ . '/../Controller/PMWeeklyReportController.php';
require_once __DIR__ . '/../Controller/PMMonthlyReportController.php';
require_once __DIR__ . '/../Controller/PMDailyReportController.php';

session_start();

/* ────────────────────────────────────────────────
   Helper Functions (ADDED)
   ──────────────────────────────────────────────── */

function sanitize_input(string $value): string {
    return trim((string)$value);
}

function get_report_controller(string $type) {
    return match ($type) {
        'daily'   => new PMDailyReportController(),
        'weekly'  => new PMWeeklyReportController(),
        'monthly' => new PMMonthlyReportController(),
        default   => new PMWeeklyReportController(),
    };
}

function get_report_data($controller): array {
    try {
        return $controller->handleRequest();
    } catch (\Throwable $e) {
        error_log('Report generation failed: ' . $e->getMessage());
        return [];
    }
}

/* ────────────────────────────────────────────────
   Access Control
   ──────────────────────────────────────────────── */

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['profile_type']) ||
    strtolower(trim((string)$_SESSION['profile_type'])) !== 'platform'
) {
    header('Location: login.php');
    exit;
}

/* ────────────────────────────────────────────────
   Inputs (Boundary owns superglobals)
   ──────────────────────────────────────────────── */

$reportType = sanitize_input($_GET['type'] ?? 'weekly');
$from = sanitize_input($_GET['from'] ?? date('Y-m-d', strtotime('-7 days')));
$to   = sanitize_input($_GET['to'] ?? date('Y-m-d'));

/* ────────────────────────────────────────────────
   Controller selection + data fetch
   ──────────────────────────────────────────────── */

$controller = get_report_controller($reportType);
$reportData = get_report_data($controller);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Platform Manager - Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f6f5ff;
        margin: 0;
        padding: 40px;
        text-align: center;
    }

    .back-btn {
        background: #6a5af9;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
        position: absolute;
        top: 20px;
        left: 20px;
    }

    .title {
        font-size: 2rem;
        color: #5b3ee4;
        margin-bottom: 20px;
    }

    .button-group {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .toggle-btn {
        background: white;
        border: 2px solid #6a5af9;
        color: #6a5af9;
        border-radius: 8px;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
    }

    .toggle-btn.active {
        background: #6a5af9;
        color: white;
    }

    .filter-section {
        margin-bottom: 20px;
        font-weight: 600;
    }

    input[type="date"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-family: inherit;
    }

    table {
        margin: 0 auto;
        border-collapse: collapse;
        width: 80%;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    thead {
        background: #6a5af9;
        color: white;
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    tr:hover {
        background: #f9f9ff;
    }

    h3 {
        color: #333;
        margin-top: 30px;
    }
</style>
</head>
<body>
    <a href="pm_dashboard.php" class="back-btn">← Back to Dashboard</a>

    <h1 class="title">Platform Manager - View Reports</h1>

    <!-- Report Type Toggle -->
    <div class="button-group">
        <a href="?type=daily" class="toggle-btn <?= $reportType === 'daily' ? 'active' : '' ?>">Daily</a>
        <a href="?type=weekly" class="toggle-btn <?= $reportType === 'weekly' ? 'active' : '' ?>">Weekly</a>
        <a href="?type=monthly" class="toggle-btn <?= $reportType === 'monthly' ? 'active' : '' ?>">Monthly</a>
    </div>

    <!-- Date Range Filter -->
    <form method="GET" class="filter-section">
        <input type="hidden" name="type" value="<?= htmlspecialchars($reportType) ?>">
        From:
        <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
        To:
        <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
        <button type="submit" class="toggle-btn active">Apply</button>
    </form>

    <!-- Report Data Table -->
    <h3><?= ucfirst($reportType) ?> Report</h3>
    <table>
        <thead>
            <tr>
                <th>
                    <?php
                        if ($reportType === 'daily') echo 'Date';
                        elseif ($reportType === 'weekly') echo 'Week';
                        else echo 'Month';
                    ?>
                </th>
                <th>CSR Count</th>
                <th>PIN Count</th>
                <th>Total Services</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($reportData)): ?>
            <?php foreach ($reportData as $row): ?>
                <tr>
                    <td><?= htmlspecialchars((string)($row['period'] ?? '-')) ?></td>
                    <td><?= htmlspecialchars((string)($row['csr_count'] ?? 0)) ?></td>
                    <td><?= htmlspecialchars((string)($row['pin_count'] ?? 0)) ?></td>
                    <td><?= htmlspecialchars((string)($row['total_services'] ?? 0)) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">No data found for the selected period.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
