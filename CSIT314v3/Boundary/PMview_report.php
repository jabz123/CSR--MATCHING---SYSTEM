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
   Helper Functions
   ──────────────────────────────────────────────── */

function sanitize_input(string $value): string {
    return trim((string)$value);
}

function normalize_date(?string $value): ?string {
    if ($value === null) {
        return null;
    }

    $value = trim($value);
    if ($value === '') {
        return null;
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        // invalid date string → treat as null or you can set a default
        return null;
    }

    // Normalise to Y-m-d for database
    return date('Y-m-d', $timestamp);
}


function get_report_controller(string $type) {
    return match ($type) {
        'daily'   => new PMDailyReportController(),
        'weekly'  => new PMWeeklyReportController(),
        'monthly' => new PMMonthlyReportController(),
        default   => new PMWeeklyReportController(),
    };
}

function get_report_data($controller, string $reportType, ?string $from, ?string $to): array {
    try {
        if ($reportType === 'daily') {
            // Daily report expects the date range
            return $controller->handleRequest($from, $to);
        }
        
        if ($reportType === 'weekly') {
            // Daily report expects the date range
            return $controller->handleRequest($from, $to);
        }

        if ($reportType === 'monthly') {
            // Daily report expects the date range
            return $controller->handleRequest($from, $to);
        }

        // Weekly / Monthly still use their own handleRequest() signature
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

// Raw input (what comes from the form)
$rawFrom = $_GET['from'] ?? null;
$rawTo   = $_GET['to'] ?? null;

// Apply defaults if nothing selected
if ($rawFrom === null || $rawFrom === '') {
    $rawFrom = date('Y-m-d', strtotime('-7 days'));
}
if ($rawTo === null || $rawTo === '') {
    $rawTo = date('Y-m-d');
}

// Boundary validates / normalises
$from = normalize_date($rawFrom);
$to   = normalize_date($rawTo);

// Values for the <input type="date"> (fall back to raw if normalisation failed)
$fromDisplay = htmlspecialchars($from ?? $rawFrom, ENT_QUOTES, 'UTF-8');
$toDisplay   = htmlspecialchars($to ?? $rawTo,   ENT_QUOTES, 'UTF-8');


/* ────────────────────────────────────────────────
   Controller selection + data fetch
   ──────────────────────────────────────────────── */

$controller = get_report_controller($reportType);
$reportData = get_report_data($controller, $reportType, $from, $to);

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
        transition: 0.3s;
    }

    .back-btn:hover {
        background: #5848d3;
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
    <!-- 🔙 Back to Dashboard Button -->
    <a href="pm_dashboard.php" class="back-btn">← Back to Dashboard</a>

    <!-- Title and Legend (centered title + right legend, no gap) -->
<div style="
  display: flex;
  justify-content: center;
  align-items: flex-start;
  position: relative;
  margin: 0 auto 20px auto;
  width: 80%;
  flex-wrap: wrap;
">
  <!-- Centered Title -->
  <h1 style="
    font-size: 2rem;
    color: #5b3ee4;
    margin: 0 auto;
    text-align: center;
    flex: 1;
  ">
    Platform Manager - View Reports
  </h1>

  <!-- Legend aligned to the right -->
  <div style="
    position: absolute;
    right: -180px;
    top: 0;
    background-color: #f3f0ff;
    border-left: 4px solid #6a5af9;
    padding: 12px 18px;
    border-radius: 8px;
    max-width: 420px;
    text-align: left;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    font-size: 14px;
  ">
    <h4 style="margin-top: 0; margin-bottom: 6px; color: #5b3ee4; font-size: 15px;">
      Legend
    </h4>
    <p style="margin: 3px 0; color: #333;">
      <strong>CSR Count</strong> — How many CSR staff was active during the selected period.
    </p>
    <p style="margin: 3px 0; color: #333;">
      <strong>PIN Count</strong> — How many PIN received help. Number of unique service requests that were fulfilled.
    </p>
    <p style="margin: 3px 0; color: #333;">
      <strong>Total Services</strong> — Total number of completed service actions (can include multiple per CSR).
    </p>
  </div>
</div>

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
        <input type="date" name="from" value="<?= $fromDisplay ?>">
        To:
        <input type="date" name="to" value="<?= $toDisplay ?>">
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
