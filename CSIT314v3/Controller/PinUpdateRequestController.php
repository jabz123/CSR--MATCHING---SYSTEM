<?php
declare(strict_types=1);

namespace App\Controller;

require_once __DIR__ . '/../Entity/requestEntity.php';
use App\Entity\requestEntity;

final class PinUpdateRequestController
{
    private requestEntity $repo;

    public function __construct(?requestEntity $repo = null)
    {
        $this->repo = $repo ?? new requestEntity();
    }

    /** Load existing request details for editing */
    public function get(int $userId, int $id): ?array
    {
        return $this->repo->getOneForUser($userId, $id);
    }

    /** Update the request (NO category change) */
    public function update(int $userId, int $id, int $category_id, string $content, string $location, string $title): bool
    {
        return $this->repo->updateForUser($userId, $id, $category_id, $content, $location, $title);
    }

}
