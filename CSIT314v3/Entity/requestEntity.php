<?php
declare(strict_types=1);

namespace App\Entity;

use App\Config\Database;
use PDO;
use PDOException;

final class requestEntity
{
    private PDO $pdo;

    public function __construct()
    {
        // ✅ Shared DB Connection
        $this->pdo = Database::getConnection();
    }

    /* ------------------------------------------------------------------
       🧾 Create new request (with category)
    ------------------------------------------------------------------ */
    public function create(int $userId, int $categoryId, string $content, string $location, string $title): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO requests (user_id, category_id, content, location, title, status, created_at)
            VALUES (:uid, :category_id, :content, :location, :title, 'open', NOW())
        ");

        return $stmt->execute([
            ':uid'         => $userId,
            ':category_id' => $categoryId,
            ':content'     => $content,
            ':location'    => $location,
            ':title'       => $title
        ]);
    }

    /* ------------------------------------------------------------------
       📌 PIN Dashboard: Recent requests by user
    ------------------------------------------------------------------ */
    public function recentByUser(int $userId, int $limit = 20): array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                r.request_id,
                r.category_id,
                c.category_name,
                r.title,
                r.content,
                r.location,
                r.status,
                r.created_at,
                r.view_count,
                r.shortlist_count
            FROM requests r
            LEFT JOIN service_categories c ON r.category_id = c.category_id
            WHERE r.user_id = :uid
            ORDER BY r.request_id DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* ------------------------------------------------------------------
       📊 PIN: Stats by user
    ------------------------------------------------------------------ */
    public function statsByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(*) AS total,
                SUM(status='open') AS open_count,
                SUM(status='in_progress') AS in_progress_count,
                SUM(status='closed') AS closed_count
            FROM requests
            WHERE user_id = :uid
        ");
        $stmt->execute([':uid' => $userId]);
        $row = $stmt->fetch() ?: [
            'total' => 0,
            'open_count' => 0,
            'in_progress_count' => 0,
            'closed_count' => 0
        ];
        foreach ($row as $k => $v) $row[$k] = (int)$v;
        return $row;
    }

    /* ------------------------------------------------------------------
       👤 PIN: List requests (with optional filters + category name)
    ------------------------------------------------------------------ */
    public function listByUser(
        int $userId,
        ?string $status = null,
        ?string $q = null,
        int $page = 1,
        int $perPage = 10
    ): array {
        $page   = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $where = "FROM requests r 
                  LEFT JOIN service_categories c ON r.category_id = c.category_id
                  WHERE r.user_id = :uid";

        $args  = [':uid' => $userId];

        if ($status !== null && $status !== '' && $status !== 'all') {
            $where .= " AND r.status = :status";
            $args[':status'] = $status;
        }

        if ($q !== null && $q !== '') {
            $where .= " AND (r.content LIKE :q OR r.title LIKE :q)";
            $args[':q'] = '%' . $q . '%';
        }

        // total count
        $stmt = $this->pdo->prepare("SELECT COUNT(*) {$where}");
        foreach ($args as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->execute();
        $total = (int)$stmt->fetchColumn();

        // fetch rows
        $sql = "
            SELECT 
                r.request_id,
                r.category_id,
                c.category_name,
                r.title,
                r.content,
                r.location,
                r.status,
                r.created_at,
                r.view_count,
                r.shortlist_count
            {$where}
            ORDER BY r.request_id DESC
            LIMIT :lim OFFSET :off
        ";

        $stmt = $this->pdo->prepare($sql);
        foreach ($args as $k => $v) { $stmt->bindValue($k, $v); }
        $stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'rows'    => $stmt->fetchAll(),
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
            'pages'   => (int)ceil($total / $perPage),
        ];
    }

    /* ------------------------------------------------------------------
       👤 PIN: Get one request (owned by user)
    ------------------------------------------------------------------ */
    public function getOneForUser(int $userId, int $requestId): ?array
    {
        $sql = "
            SELECT 
                r.request_id AS id, 
                r.user_id, 
                r.category_id, 
                c.category_name,
                r.content, 
                r.location, 
                r.created_at, 
                r.title, 
                r.status,
                r.view_count, 
                r.shortlist_count
            FROM requests r
            LEFT JOIN service_categories c ON r.category_id = c.category_id
            WHERE r.request_id = :rid AND r.user_id = :uid
            LIMIT 1
        ";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([':rid' => $requestId, ':uid' => $userId]);
        $row = $stm->fetch();
        return $row ?: null;
    }

    /* ------------------------------------------------------------------
       ✏️ Update request (only if belongs to user)
    ------------------------------------------------------------------ */
    public function updateForUser(
        int $userId,
        int $requestId,
        int $category_id,
        string $content,
        string $location,
        string $title
    ): bool {
        $sql = "
            UPDATE requests
            SET category_id = :category_id, content = :content, location = :location, title = :title
            WHERE request_id = :rid AND user_id = :uid
        ";
        $stm = $this->pdo->prepare($sql);
        return $stm->execute([
            ':category_id' => $category_id,
            ':title'       => $title,
            ':content'     => $content,
            ':location'    => $location,
            ':rid'         => $requestId,
            ':uid'         => $userId,
        ]);
    }

    /* ------------------------------------------------------------------
       🧑‍💼 CSR: Fetch all requests
    ------------------------------------------------------------------ */
    public function readAllRequests(): array
    {
        $sql = "
            SELECT
                r.request_id,
                r.category_id,
                c.category_name,
                r.title,
                r.content,
                r.location,
                r.status,
                r.view_count,
                r.shortlist_count,
                r.created_at
            FROM requests r
            LEFT JOIN service_categories c ON r.category_id = c.category_id
            ORDER BY r.created_at DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ------------------------------------------------------------------
       🧾 CSR: Get request details
    ------------------------------------------------------------------ */
    public function getRequestById(int $requestId): ?array
    {
        $sql = "
            SELECT
                r.request_id AS id,
                r.user_id,
                r.category_id,
                c.category_name,
                r.title,
                r.content,
                r.status,
                r.view_count,
                r.shortlist_count,
                r.location,
                r.created_at,
                u.name AS homeowner_name
            FROM requests r
            LEFT JOIN service_categories c ON r.category_id = c.category_id
            LEFT JOIN users u ON r.user_id = u.id
            WHERE r.request_id = :id
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $requestId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /* ------------------------------------------------------------------
       🔍 CSR: Search Open Requests
    ------------------------------------------------------------------ */
    public function searchOpenRequests(string $keyword): array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                r.request_id, 
                r.category_id,
                c.category_name,
                r.title, 
                r.content, 
                r.location, 
                r.status, 
                r.view_count, 
                r.shortlist_count, 
                r.created_at
            FROM requests r
            LEFT JOIN service_categories c ON r.category_id = c.category_id
            WHERE r.status = 'open'
              AND (r.title LIKE :kw OR r.content LIKE :kw OR r.location LIKE :kw)
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([':kw' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ------------------------------------------------------------------
       ❌ Delete Request Owned by User
    ------------------------------------------------------------------ */
    public function hardDeleteForUser(int $userId, int $requestId): bool
    {
        $sql = "DELETE FROM requests WHERE request_id = :rid AND user_id = :uid LIMIT 1";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([':rid' => $requestId, ':uid' => $userId]);
        return $stm->rowCount() === 1;
    }

    /* ------------------------------------------------------------------
       Update View Count
    ------------------------------------------------------------------ */
    public function incrementViewCount(int $requestId): bool
    {
        $sql = "UPDATE requests SET view_count = view_count + 1 WHERE request_id = :rid";
        $stm = $this->pdo->prepare($sql);
        return $stm->execute([':rid' => $requestId]);
    }

    /* ------------------------------------------------------------------
       Update Shortlist Count
    ------------------------------------------------------------------ */
    public function incrementShortlistCount(int $requestId): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE requests 
                SET shortlist_count = shortlist_count + 1 
                WHERE request_id = :rid
            ");
            return $stmt->execute([':rid' => $requestId]);
        } catch (PDOException $e) {
            error_log('incrementShortlistCount failed: ' . $e->getMessage());
            return false;
        }
    }

    public function getCategories(): array
    {
        $stmt = $this->pdo->query("
            SELECT category_id, category_name
            FROM service_categories
            ORDER BY category_name ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
