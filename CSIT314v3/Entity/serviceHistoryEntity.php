<?php
declare(strict_types=1);

namespace App\Entity;

use App\Config\Database;
use PDO;
use PDOException;

final class serviceHistoryEntity
{
    private PDO $pdo;

    public function __construct()
    {
        // ✅ Shared DB Connection
        $this->pdo = Database::getConnection();
    }

    /**
     * 🔍 Search completed service history
     */
    public function searchHistory(
        int $csrId,
        ?string $keyword = '',
        ?string $startDate = ''
    ): array {
        // ⚠️ TEMP: force CSR ID = 26 (remove when ready)
        $csrId = 26;

        $sql = "
            SELECT 
                service_id,
                csr_id,
                request_id,
                volunteer_id,
                status,
                completed_at,
                hours_served,
                remarks
            FROM service_history
            WHERE csr_id = :csrId
              AND status = 'completed'
        ";

        $params = [':csrId' => $csrId];

        // Keyword search
        if (!empty($keyword)) {
            $sql .= " AND remarks LIKE :kw";
            $params[':kw'] = '%' . $keyword . '%';
        }

        // ✅ Start date filter (single date only)
        if (!empty($startDate)) {
            $dt = \DateTime::createFromFormat('Y-m-d', $startDate);
            if ($dt && $dt->format('Y-m-d') === $startDate) {
                $sql .= " AND DATE(completed_at) = :startDate";
                $params[':startDate'] = $startDate;
            }
        }

        $sql .= " ORDER BY completed_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * 📜 Get all completed services for a CSR
     */
    public function getHistory(int $csrId): array
    {
        $sql = "
            SELECT 
                service_id,
                csr_id,
                request_id,
                volunteer_id,
                status,
                completed_at,
                hours_served,
                remarks
            FROM service_history
            WHERE csr_id = :csrId
              AND status = 'completed'
            ORDER BY completed_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':csrId' => $csrId]);
        return $stmt->fetchAll();
    }
}
