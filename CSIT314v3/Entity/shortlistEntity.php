<?php
declare(strict_types=1);

namespace App\Entity;

use App\Config\Database;
use PDO;
use PDOException;

final class shortlistEntity
{
    private PDO $pdo;

    public function __construct()
    {
        // ✅ Shared DB Connection
        $this->pdo = Database::getConnection();
    }

    public function existsInShortlist(int $csrId, int $requestId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM csr_shortlist 
            WHERE csr_id = :cid AND request_id = :rid
        ");
        $stmt->execute([':cid' => $csrId, ':rid' => $requestId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function addToShortlist(int $csrId, int $requestId): bool
    {
        try {
            // ✅ Prevent duplicates
            if ($this->existsInShortlist($csrId, $requestId)) {
                return false;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO csr_shortlist (csr_id, request_id, created_at)
                VALUES (:cid, :rid, NOW())
            ");
            return $stmt->execute([':cid' => $csrId, ':rid' => $requestId]);
        } catch (PDOException $e) {
            error_log('Shortlist insert failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getShortlistByCSR(int $csrId): array
    {
        $sql = "
            SELECT 
                s.id AS shortlist_id,
                s.created_at AS added_at,
                r.request_id,
                r.title,
                r.location,
                r.status
            FROM csr_shortlist s
            INNER JOIN requests r ON s.request_id = r.request_id
            WHERE s.csr_id = :csr_id
            ORDER BY s.created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':csr_id' => $csrId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchShortlist(int $csrId, string $keyword = ''): array
    {
        $sql = "
            SELECT 
                s.id, 
                r.title, 
                r.location, 
                r.status, 
                r.created_at
            FROM csr_shortlist s
            JOIN requests r ON s.request_id = r.request_id
            WHERE s.csr_id = :cid
        ";

        if (!empty($keyword)) {
            $sql .= " AND (r.title LIKE :kw OR r.location LIKE :kw)";
        }

        $sql .= " ORDER BY s.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cid', $csrId, PDO::PARAM_INT);

        if (!empty($keyword)) {
            $stmt->bindValue(':kw', "%{$keyword}%", PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
