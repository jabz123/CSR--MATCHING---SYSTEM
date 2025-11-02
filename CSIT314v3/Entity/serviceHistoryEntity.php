<?php
declare(strict_types=1);

namespace App\Entity;

use PDO;

final class serviceHistoryEntity {
    private PDO $pdo;

    public function __construct(
        string $host = '127.0.0.1',
        string $db   = 'csit314',
        string $user = 'root',
        string $pass = ''
    ) {
        $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    /**
     * 🔍 Search completed service history from service_history table directly
     */
    public function searchHistory(int $csrId, ?string $keyword = '', ?string $startDate = '', ?string $endDate = ''): array
    {
        // ⚠️ Force CSR ID = 26 temporarily
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

        // Keyword (service name) filter
        if ($keyword !== null && $keyword !== '') {
            $sql .= " AND remarks LIKE :kw";
            $params[':kw'] = '%' . $keyword . '%';
        }

        // Date validation helper (YYYY-MM-DD)
        $isValidDate = static function (?string $d): bool {
            if (!$d) return false;
            $dt = \DateTime::createFromFormat('Y-m-d', $d);
            return $dt && $dt->format('Y-m-d') === $d;
        };

        $hasStart = $isValidDate($startDate);
        $hasEnd   = $isValidDate($endDate);

        if ($hasStart && $hasEnd) {
            $sql .= " AND completed_at BETWEEN :startDt AND :endDt";
            $params[':startDt'] = $startDate . ' 00:00:00';
            $params[':endDt']   = $endDate   . ' 23:59:59';
        } elseif ($hasStart) {
            $sql .= " AND DATE(completed_at) = :startDate";
            $params[':startDate'] = $startDate;
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
?>
