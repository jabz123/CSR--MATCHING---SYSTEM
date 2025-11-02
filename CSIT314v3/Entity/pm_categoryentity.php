<?php
declare(strict_types=1);

namespace App\Entity;

final class pm_categoryentity
{
    private \PDO $db;

    public function __construct()
    {
        // Put your own credentials here
        $host = 'localhost';
        $db   = 'csit314';
        $user = 'root';
        $pass = '';

        $this->db = new \PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]
        );
    }

    /** Insert category; return false if duplicate/DB error */
    public function insertCategory(string $categoryName): bool
    {
        try {
            $sql = "INSERT INTO service_categories (category_name) VALUES (:name)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':name' => $categoryName]);
        } catch (\PDOException $e) {
            // UNIQUE violation or other DB error -> inform controller via false
            return false;
        }
    }

    /** @return array<int, array{category_id:int,category_name:string,created_at:string,updated_at:string}> */
    public function fetchAllCategories(): array
    {
        $sql = "SELECT category_id, category_name, created_at, updated_at
                  FROM service_categories
                 ORDER BY category_name ASC";
        return $this->db->query($sql)->fetchAll();
    }
      /** Update category name by ID; returns bool for success/failure */
    public function updateCategory(int $categoryId, string $newName): bool
    {
        try {
            $sql = "UPDATE service_categories 
                    SET category_name = :name, updated_at = NOW()
                    WHERE category_id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':name' => $newName,
                ':id'   => $categoryId
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    /** Fetch single category by ID */
    public function fetchCategoryById(int $id): ?array
    {
        $sql = "SELECT category_id, category_name, created_at, updated_at 
                  FROM service_categories
                 WHERE category_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }
        /** Delete category by ID; returns true if successful */
public function deleteCategory(int $id): bool
{
    try {
        $sql = "DELETE FROM service_categories WHERE category_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    } catch (\PDOException $e) {
        error_log("DeleteCategory error: " . $e->getMessage());
        return false;
    }
}
public function searchCategories(string $term): array
{
    if (trim($term) === '') {
        $stmt = $this->db->query("SELECT * FROM service_categories ORDER BY created_at DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    $stmt = $this->db->prepare("
        SELECT * FROM service_categories 
        WHERE category_name LIKE :term 
        ORDER BY created_at DESC
    ");
    $stmt->execute([':term' => '%' . $term . '%']);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}




}
