<?php
declare(strict_types=1);

namespace App\Entity;

use App\Config\Database;
use PDO;
use PDOException;

final class pinHistoryEntity
{
    private PDO $pdo;

    public function __construct()
    {
        // Use shared database connection
        $this->pdo = Database::getConnection();
    }

    /* ------------------------------------------------------------------
       Retrieve history array by pin_id from db
    ------------------------------------------------------------------ */
    public function listHistoryByPin(
        int $pinId,
        ?string $status = null,
        int $limit = 200,
        int $offset = 0
    ): array {
        // TEMP: force to demo PIN id 2
        $forcedPinId = 2;

        $hasStatus = $status !== null && $status !== '';

        // sanitize limit/offset to safe integers
        $limit  = max(1, (int)$limit);
        $offset = max(0, (int)$offset);

        $sql = "
            SELECT
                h.history_id, h.request_id, h.volunteer_id, h.status, h.completed_at,
                h.title, h.description
            FROM pin_history h
            JOIN requests r ON r.request_id = h.request_id
            WHERE h.pin_id = :forced_pin_id
            " . ($hasStatus ? "AND h.status = :status" : "") . "
            ORDER BY h.history_id ASC
            LIMIT $limit OFFSET $offset
        ";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':forced_pin_id', $forcedPinId, PDO::PARAM_INT);
            if ($hasStatus) {
                $stmt->bindValue(':status', strtolower($status), PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit('❌ Query failed: ' . $e->getMessage());
        }
    }

    /* ------------------------------------------------------------------
       Search history by keywords and/or date range
    ------------------------------------------------------------------ */
    public function searchCompleted(
        int $pinId,
        ?string $q = null,
        ?string $date = null,
        int $limit = 100,
        int $offset = 0
    ): array {
        $forcedPinId = 2;
        $q = ($q !== null) ? trim($q) : null;

        $sql = "
            SELECT h.history_id, h.request_id, h.volunteer_id, h.status, h.completed_at,
                h.title, h.description
            FROM pin_history h
            JOIN requests r ON r.request_id = h.request_id
            WHERE h.pin_id = :pin_id
            AND h.status = 'completed'
        ";

        $params = [ ':pin_id' => $forcedPinId ];

        if ($q) {
            $sql .= " AND (h.title LIKE :q OR h.description LIKE :q)";
            $params[':q'] = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';
        }

        if ($date) {
            $start = $date . ' 00:00:00';
            $end   = $date . ' 23:59:59';
            $sql .= " AND h.completed_at BETWEEN :start AND :end";
            $params[':start'] = $start;
            $params[':end'] = $end;
        }

        $sql .= " ORDER BY h.history_id ASC LIMIT $limit OFFSET $offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
