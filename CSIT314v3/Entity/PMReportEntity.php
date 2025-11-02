<?php
declare(strict_types=1);

namespace App\Entity;

use App\Config\Database;
use PDO;

final class PMReportEntity
{
    private PDO $pdo;

    public function __construct()
    {
        // ✅ Shared DB Connection via config/Database
        $this->pdo = Database::getConnection();
    }

    public function getWeeklyReport(?string $from = null, ?string $to = null): array
    {
        $where = "WHERE status = 'completed'";
        $params = [];

        if (!empty($from)) {
            $where .= " AND completed_at >= :from";
            $params[':from'] = $from . " 00:00:00";
        }

        if (!empty($to)) {
            $where .= " AND completed_at <= :to";
            $params[':to'] = $to . " 23:59:59";
        }

        $sql = "
            SELECT 
                CONCAT('Week ', WEEK(completed_at, 1), ' (', YEAR(completed_at), ')') AS period,
                COUNT(DISTINCT csr_id) AS csr_count,
                COUNT(DISTINCT request_id) AS pin_count,
                COUNT(*) AS total_services
            FROM service_history
            $where
            GROUP BY YEAR(completed_at), WEEK(completed_at, 1)
            ORDER BY YEAR(completed_at), WEEK(completed_at, 1)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }

    public function getMonthlyReport(?string $from = null, ?string $to = null): array
    {
        $where = "WHERE status = 'completed'";
        $params = [];

        if (!empty($from)) {
            $where .= " AND completed_at >= :from";
            $params[':from'] = $from . " 00:00:00";
        }

        if (!empty($to)) {
            $where .= " AND completed_at <= :to";
            $params[':to'] = $to . " 23:59:59";
        }

        $sql = "
            SELECT 
                DATE_FORMAT(completed_at, '%M %Y') AS period,
                COUNT(DISTINCT csr_id) AS csr_count,
                COUNT(DISTINCT request_id) AS pin_count,
                COUNT(*) AS total_services
            FROM service_history
            $where
            GROUP BY YEAR(completed_at), MONTH(completed_at)
            ORDER BY YEAR(completed_at), MONTH(completed_at)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }

    public function getDailyReport(?string $from = null, ?string $to = null): array
    {
        $where = "WHERE status = 'completed'";
        $params = [];

        if (!empty($from)) {
            $where .= " AND DATE(completed_at) >= :from";
            $params[':from'] = $from;
        }

        if (!empty($to)) {
            $where .= " AND DATE(completed_at) <= :to";
            $params[':to'] = $to;
        }

        $sql = "
            SELECT 
                DATE_FORMAT(completed_at, '%Y-%m-%d') AS period,
                COUNT(DISTINCT csr_id) AS csr_count,
                COUNT(DISTINCT request_id) AS pin_count,
                COUNT(*) AS total_services
            FROM service_history
            $where
            GROUP BY DATE(completed_at)
            ORDER BY DATE(completed_at)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }
}
