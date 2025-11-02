<?php
declare(strict_types=1);

namespace App\Entity;

use PDO;
use PDOException;

final class shortlistEntity
{
    private PDO $pdo;

    public function __construct(
        string $host = '127.0.0.1',
        string $db   = 'csit314',
        string $user = 'root',
        string $pass = ''
    ) {
        try {
            $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            exit('❌ DB Connection failed: ' . $e->getMessage());
        }
    }

    private function createPdo(): PDO
    {
        // TODO: replace with your actual DSN/creds or include a config
        $dsn = 'mysql:host=localhost;dbname=csit314;charset=utf8mb4';
        $user = 'root';
        $pass = '';
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }

    public function existsInShortlist(int $csrId, int $requestId): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM csr_shortlist WHERE csr_id = :cid AND request_id = :rid
        ");
        $stmt->execute([':cid' => $csrId, ':rid' => $requestId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // public function addToShortlist(int $csrId, int $requestId): bool
    // {
    //     try {
    //         // ✅ Prevent duplicates
    //         $check = $this->pdo->prepare("
    //             SELECT COUNT(*) FROM csr_shortlist
    //             WHERE csr_id = :cid AND request_id = :rid
    //         ");
    //         $check->execute([':cid' => $csrId, ':rid' => $requestId]);
    //         if ((int)$check->fetchColumn() > 0) {
    //             return false; // Already exists
    //         }

    //         // ✅ Insert new record
    //         $stmt = $this->pdo->prepare("
    //             INSERT INTO csr_shortlist (csr_id, request_id, created_at)
    //             VALUES (:cid, :rid, NOW())
    //         ");
    //         return $stmt->execute([':cid' => $csrId, ':rid' => $requestId]);
    //     } catch (PDOException $e) {
    //         error_log("Shortlist insert failed: " . $e->getMessage());
    //         return false;
    //     }
    // }

    public function addToShortlist(int $csrId, int $requestId): bool
    {
        try {
            // Optional: pre-check to avoid unique-key exception noise
            if ($this->existsInShortlist($csrId, $requestId)) {
                return false;
            }

            $stmt = $this->pdo->prepare('
                INSERT INTO csr_shortlist (csr_id, request_id, created_at)
                VALUES (:cid, :rid, NOW())
            ');
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
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

public function searchShortlist(int $csrId, string $keyword = ''): array
{
    $sql = "
        SELECT s.id, r.title, r.location, r.status, r.created_at
        FROM csr_shortlist s
        JOIN requests r ON s.request_id = r.request_id
        WHERE s.csr_id = :cid
    ";

    // Add keyword filtering (title or location)
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