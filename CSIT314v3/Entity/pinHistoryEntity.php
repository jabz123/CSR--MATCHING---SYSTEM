<?php
declare(strict_types=1);

namespace App\Entity;

use PDO;
use PDOException;

final class pinHistoryEntity
{
    private PDO $pdo;

    public function __construct(
        string $host = '127.0.0.1',
        string $db   = 'csit314',   // <-- make sure this matches your actual DB (e.g. csit314v3)
        string $user = 'root',
        string $pass = ''
    ) {
        try {
            $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            exit('❌ Database connection failed: ' . $e->getMessage());
        }
    }

    public function findByPin(
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

        // If you added pin_id to pin_history (recommended), use h.pin_id
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
            $stmt->bindValue(':forced_pin_id', $forcedPinId, \PDO::PARAM_INT);
            if ($hasStatus) {
                $stmt->bindValue(':status', strtolower($status), \PDO::PARAM_STR);
            }
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            exit('❌ Query failed: ' . $e->getMessage());
        }
    }

    public function searchCompleted(
    int $pinId,
    ?string $q = null,
    ?string $from = null,  // 'YYYY-MM-DD' or null
    ?string $to   = null,  // 'YYYY-MM-DD' or null
    int $limit = 100,
    int $offset = 0
    ): array {
        // TEMP: force to demo PIN id 2 (ignores $pinId argument)
        $forcedPinId = 2;

        // --- Normalize inputs ---
        $q = ($q !== null) ? trim($q) : null;

        $fromDT = $from ? \DateTime::createFromFormat('Y-m-d', trim($from)) : null;
        $toDT   = $to   ? \DateTime::createFromFormat('Y-m-d', trim($to))   : null;

        $fromSql = $fromDT ? $fromDT->format('Y-m-d 00:00:00') : null;
        $toSql   = $toDT   ? $toDT->format('Y-m-d 23:59:59')   : null;

        if ($fromSql && $toSql && $fromSql > $toSql) { [$fromSql, $toSql] = [$toSql, $fromSql]; }

        // Sanitize limit/offset and inline (binding can be flaky on MySQL without emulate prepares)
        $limit  = max(1, (int)$limit);
        $offset = max(0, (int)$offset);

        // Escape % and _ in the search string so literals work as expected
        $like = null;
        if ($q !== null && $q !== '') {
            $like = strtr($q, [
                '\\' => '\\\\', // escape backslash first
                '%'  => '\%',
                '_'  => '\_',
            ]);
            $like = '%' . $like . '%';
        }

        // --- Build SQL ---
        // NOTE: We filter by h.pin_id (new column you added), not r.user_id
        $sql = "
            SELECT
                h.history_id, h.request_id, h.volunteer_id, h.status, h.completed_at,
                h.title, h.description
            FROM pin_history h
            JOIN requests r ON r.request_id = h.request_id
            WHERE h.pin_id = :pin_id
            AND h.status = 'completed'
        ";

        $params = [ ':pin_id' => $forcedPinId ];

        if ($like !== null) {
            $sql .= " AND (h.title LIKE :q ESCAPE '\\\\' OR h.description LIKE :q ESCAPE '\\\\') ";
            $params[':q'] = $like;
        }
        if ($fromSql) { $sql .= " AND h.completed_at >= :from "; $params[':from'] = $fromSql; }
        if ($toSql)   { $sql .= " AND h.completed_at <= :to   "; $params[':to']   = $toSql;   }

        $sql .= " ORDER BY h.history_id ASC
                LIMIT $limit OFFSET $offset ";

        // --- Execute ---
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



    // public function searchCompleted(
    // int $pinId,
    // ?string $q = null,
    // ?string $from = null,  // raw 'YYYY-MM-DD' or null
    // ?string $to   = null,  // raw 'YYYY-MM-DD' or null
    // int $limit = 100,
    // int $offset = 0
    // ): array {
    //     // normalize here (entity-level)
    //     $q = ($q !== null) ? trim($q) : null;

    //     $fromDT = $from ? \DateTime::createFromFormat('Y-m-d', trim($from)) : null;
    //     $toDT   = $to   ? \DateTime::createFromFormat('Y-m-d', trim($to))   : null;

    //     $fromSql = $fromDT ? $fromDT->format('Y-m-d 00:00:00') : null;
    //     $toSql   = $toDT   ? $toDT->format('Y-m-d 23:59:59')   : null;

    //     if ($fromSql && $toSql && $fromSql > $toSql) { [$fromSql,$toSql] = [$toSql,$fromSql]; }

    //     $sql = "
    //     SELECT h.history_id, h.request_id, h.volunteer_id, h.status, h.completed_at,
    //             h.title, h.description
    //     FROM pin_history h
    //     JOIN requests r ON r.request_id = h.request_id
    //     WHERE r.user_id = :pin_id
    //         AND h.status = 'completed'
    //     ";

    //     $params = [':pin_id' => $pinId];

    //     if ($q !== null && $q !== '') {
    //         $sql .= " AND (h.title LIKE :q OR h.description LIKE :q) ";
    //         $params[':q'] = '%'.$q.'%';
    //     }
    //     if ($fromSql) { $sql .= " AND h.completed_at >= :from "; $params[':from'] = $fromSql; }
    //     if ($toSql)   { $sql .= " AND h.completed_at <= :to   "; $params[':to']   = $toSql;   }

    //     $sql .= " ORDER BY h.history_id ASC
    //             LIMIT :limit OFFSET :offset ";

    //     $stmt = $this->pdo->prepare($sql);
    //     foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
    //     $stmt->bindValue(':limit',  $limit,  \PDO::PARAM_INT);
    //     $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
    //     $stmt->execute();

    //     return $stmt->fetchAll();
    // }

}
